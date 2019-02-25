<!-- NavBar For Authenticated Users -->
<spark-navbar
    :user="user"
    :teams="teams"
    :current-team="currentTeam"
    :has-unread-notifications="hasUnreadNotifications"
    :has-unread-announcements="hasUnreadAnnouncements"
    inline-template="true">
    
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('/admin') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">PA</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">
                <!--<img class="img-responsive" src="/build/img/logo-sm-white.png" alt="logo"  />-->
               Printable
            </span>
        </a>
            
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        
            <div class="navbar-custom-menu">
                
                <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @includeIf('spark::nav.user-left')
                    </ul>
    
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right ">
                        @includeIf('spark::nav.user-right')
    
                        <!-- Notifications -->
                        <li>
                            <a @click="showNotifications" class="has-activity-indicator">
                                <div class="navbar-icon">
                                    <i class="activity-indicator" v-if="hasUnreadNotifications || hasUnreadAnnouncements"></i>
                                    <i class="icon fa fa-bell"></i>
                                </div>
                            </a>
                        </li>
    
                        <li class="dropdown pr-20">
                            <!-- User Photo / Name -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }}
                                <img :src="user.photo_url" class="spark-nav-profile-photo m-r-xs">
                                <span class="caret"></span>
                            </a>
    
                            <ul class="dropdown-menu" role="menu">
                            
                                <!-- Impersonation -->
                                @if (session('spark:impersonator'))
                                    <li class="dropdown-header">@lang('labels.impersonation')</li>
    
                                    <!-- Stop Impersonating -->
                                    <li>
                                        <a href="/spark/kiosk/users/stop-impersonating">
                                            <i class="fa fa-fw fa-btn fa-user-secret"></i>
                                            Back To My Account
                                        </a>
                                    </li>
    
                                    <li class="divider"></li>
                                @endif
    
                                <!-- Developer -->
                                @if (Spark::developer(Auth::user()->email))
                                    @include('spark::nav.developer')
                                @endif
    
                                <!-- Subscription Reminders -->
                                {{--@include('spark::nav.subscriptions')--}}
    
                                <!-- Settings -->
                                <li class="dropdown-header">@lang('labels.settings')</li>
    
                                    <!-- Your Settings -->
                                    <li>
                                        <a href="{{ url('/settings') }}">
                                            <i class="fa fa-fw fa-btn fa-cog"></i>
                                            @lang('actions.my_account')
                                        </a>
                                    </li>
                                        
                                    @if (Spark::usesTeams())
                                        <!-- Team Settings -->
                                        @include('spark::nav.teams')
                                    @endif
    
                                <li class="divider"></li>
    
                                <!-- Logout -->
                                <li>
                                    <a href="/logout">
                                        <i class="fa fa-fw fa-btn fa-sign-out"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                
            </div>
        </nav>
    </header>
</spark-navbar>
