<?php

namespace App\Notifications;

use App\User;
use App\Alu;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewAlu extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $alu;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Alu $alu)
    {
        $this->user = $user;
        $this->alu = $alu;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->alu->type == 'A') {
            $type_name = 'Absence';
        }
        elseif ($this->alu->type == 'L') {
            $type_name = 'Late';
        }
        elseif ($this->alu->type == 'U') {
            $type_name = 'Undertime';
        }

        $url = '/aluforms/file/' . base64_encode($this->alu->id .' '. $this->alu->date);
        $date_of_alu = date('D, j M Y', strtotime($this->alu->date));
        $time_of_alu = ($this->alu->time) ? $this->alu->time : 'N/A';
        $date_alu_due = date('D, j M Y', strtotime($this->alu->date_alu_due));

        return (new MailMessage)
                    ->subject('New ALU (' . $type_name . ')')
                    ->greeting('New ' . $type_name)
                    ->line('Employee Name: ' . $this->user->last_name .', '. $this->user->first_name .' '. $this->user->middle_name)
                    ->line('Employee No.: ' . $this->user->employee_num)
                    ->line("Date of $type_name: " . $date_of_alu)
                    ->line("Time of $type_name: " . $time_of_alu)
                    ->line("Due Date of ALU Form: " . $date_alu_due)
                    ->action('File ALU', url($url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
