<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>

    <!-- Search -->
    <?php echo $this->element('Backend.AdminLte/navbar_search'); ?>


    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

            <!-- Content Menu -->
            <?php echo $this->element('Backend.AdminLte/navbar_content_menu'); ?>

            <!-- Messages Menu -->
            <?php echo $this->element('Backend.AdminLte/navbar_messages'); ?>

            <!-- Notifications Menu -->
            <?php echo $this->element('Backend.AdminLte/navbar_notifications'); ?>

            <!-- Tasks Menu -->
            <?php echo $this->element('Backend.AdminLte/navbar_tasks'); ?>

            <!-- User Account Menu -->
            <?php echo $this->element('Backend.AdminLte/navbar_user'); ?>

            <!-- Control Sidebar Toggle Button -->
            <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
        </ul>
    </div>
</nav>