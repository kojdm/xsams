<nav class="navbar navbar-expand-md navbar-dark navbar-laravel bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            XSAMS
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                
                {{-- Views for ADMINS --}}
                @auth('admin')
                    <li><a class="nav-link" href="/admin/timekeeping">Timekeeping</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ALU Forms
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/admin/aluforms/pending">Pending ALU Forms</a>
                            <a class="dropdown-item" href="/admin/aluforms">Completed ALU Forms</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/admin/alus/export">Export ALUs</a>                                    
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            LOA Forms
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/admin/loaforms/pending">Pending LOA Forms</a>
                            <a class="dropdown-item" href="/admin/loaforms">Completed LOA Forms</a>                                
                        </div>
                    </li>
                    <li><a class="nav-link" href="/admin/attendancerecords/u={{uniqid()}}">Attendance Records</a></li>
                    <li><a class="nav-link" href="/admin/users">Employees</a></li>
                @endauth

                {{-- Views for USERS/EMPLOYEES --}}
                @auth('web')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ALU Forms
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/aluforms">Sent ALU Forms</a>
                            <a class="dropdown-item" href="/aluforms/expired">Expired ALUs</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/aluforms/create"><strong>Create ALU Form</strong></a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            LOA Forms
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/loaforms">Sent LOA Forms</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/loaforms/create"><strong>Create LOA Form</strong></a>
                        </div>
                    </li>

                    <li><a class="nav-link" href="/attendancerecord">Attendance Record</a></li>

                    {{-- Views for SUPERVISORS --}}
                    @if(Auth::user()->hasRole('supervisor'))
                        <li><a class="nav-link" href="/users">Employees</a></li>
                    @endif
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @auth('admin')    
                                <strong>ADMIN</strong> <span class="caret"></span>
                            @else
                                <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong> <span class="caret"></span>
                            @endauth
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @auth('web')
                                <a class="dropdown-item" href="/changepassword">Change Password</a>
                                <div class="dropdown-divider"></div>
                            @endauth
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>