----

# Xavier School Attendance Management System

----

Final thesis project of the Management Information Systems program in Ateneo de Manila University. Our client, Xavier School, had issues with their attendance management system, including dated technology, lost source code, and lack of automation. Upon review of their case, our project team, Timwork Solutions, developed an improved attendance management system addressing the client's needs. It's functions include automatic updating of employee attendance records from bio-metric scanners, filing of sick/vacation leaves,  and generation of employee attendance summaries. A web application built on Laravel and hosted on the client's in-house servers.

## Requirements
* Operating System: Windows 7 & up
* [XAMPP for Windows 7.24](https://www.apachefriends.org/download.html)
* [Composer](https://getcomposer.org/download/)

----

## Setup
### Composer
1. Open the **timwork-xsams** project folder and rename `.env.example` file to `.env`
2. Open a terminal or command line, `cd` to the timwork-xsams folder, and run `composer install`

### VirtualHost Configuration
1. Navigate to this directory: `C:\xampp\apache\conf\extra`
2. Look for a file called `httpd-vhosts.conf` and open it on your favorite text editor.
3. Go to the very bottom of the text, add these lines, and save the file:

```
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/"
    ServerName localhost
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/timwork- 
    xsams/public"
    ServerName xsams.test
</VirtualHost>
```

### Localhost Configuration
1. Run **Notepad** as adminstrator.
2. Open the `hosts` file found in `C:\Windows\System32\drivers\etc\`
3. Go the the very bottom of the text, add these lines, and save the file:

``` 
127.0.0.1 localhost
127.0.0.1 xsams.test
```

### Database Setup
1. Open the **XAMPP Control Panel**.
2. Start both the **Apache** and **MySQL** services
3. Open your web browser and enter `localhost/phpmyadmin` in the address bar.
4. In **phpMyAdmin**, create a new database called `xsams`.
5. Open a terminal or command line and `cd` to the timwork-xsams folder.
6. Migrate and seed the database by running `php artisan migrate --seed`.

----

## Usage
1. Make sure both **Apache** and **MySQL** are running in the XAMPP Control Panel.
2. Access the system by visiting `http://xsams.test` in your web browser.
3. Login to **XSAMS** by using any of the following login credentials:

```
ADMIN
admin@xs.edu.ph        |   password

EMPLOYEES
cking@xs.edu.ph        |   password   (Supervisor - Accounting)
rmcdonald@xs.edu.ph    |   password   (Accounting)
minasal@xs.edu.ph      |   password   (Athletics)
jbee@xs.edu.ph         |   password   (NEXT)
```

----
