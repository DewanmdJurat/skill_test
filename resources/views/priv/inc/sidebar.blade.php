<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title">
            Navigation
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
            
                <ul class="nav nav-main">
                    <li>
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>                        
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('division') }}">
                            <i class="fas fa-tag" aria-hidden="true"></i>
                            <span>Division</span>
                        </a>                        
                    </li>

                    <li>
                        <a class="nav-link" href="{{ route('district') }}">
                            <i class="fas fa-mountain" aria-hidden="true"></i>
                            <span>District</span>
                        </a>                        
                    </li>

                    <li>
                        <a class="nav-link" href="{{ route('upazila') }}">
                            <i class="fas fa-chevron-up" aria-hidden="true"></i>
                            <span>Upazila</span>
                        </a>                        
                    </li>

                    <li>
                        <a class="nav-link" href="{{ route('union') }}">
                            <i class="fas fa-play" aria-hidden="true"></i>
                            <span>Union</span>
                        </a>                        
                    </li>

                    <li>
                        <a class="nav-link" href="{{ route('designation') }}">
                            <i class="fas fa-user-tag" aria-hidden="true"></i>
                            <span>Designation</span>
                        </a>                        
                    </li>

                    <li>
                        <a class="nav-link" href="{{ route('user') }}">
                            <i class="fas fa-users" aria-hidden="true"></i>
                            <span>User</span>
                        </a>                        
                    </li>
                    
                </ul>
            </nav>
        </div>

        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    
                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
    </div>

</aside>