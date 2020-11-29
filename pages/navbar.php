        <nav class="navbar navbar-expand-md scrolling-navbar">
            <a class="navbar-brand-xl" href="?page=dashboard"><img src="images/logo.png" alt="Opencasetracker Logo" width="35" height="35" class="d-inline-block align-top" /></a>
            <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main-navigation">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=dashboard">Home</a>
                    </li>     
                    <li class="nav-item">
                        <a class="nav-link" href="?page=cases">Cases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=reports">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=options">Options</a>
                    </li>
                    <?php
                        if(isset($admin) && $admin) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=admin">Admin</a>
                    </li>
                    <?php
                        }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=account">Account</a>
                    </li>
                    <li class="nav-link">
                        <input id="goto" class="form-control p-0 h-auto w-auto text-center" type="text" size="2" placeholder="##" />
                    </li>
                    
                </ul>
            </div>
            
        </nav>

