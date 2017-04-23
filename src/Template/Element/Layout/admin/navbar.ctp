<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu" style="float: left;">
        <ul class="nav navbar-nav">
            <li><?= $this->Html->link(__('Frontend'), '/', ['target' => '_blank']); ?></li>

            <!-- Content Menu -->
            <?php echo $this->element('Backend.Layout/admin/navbar_content_menu'); ?>
        </ul>
    </div>


    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

            <!-- Search -->
            <?php echo $this->element('Backend.Layout/admin/navbar_search'); ?>


            <!-- Messages Menu -->
            <?php //echo $this->element('Backend.Layout/admin/navbar_messages'); ?>

            <!-- Notifications Menu -->
            <?php //echo $this->element('Backend.Layout/admin/navbar_notifications'); ?>

            <!-- Tasks Menu -->
            <?php //echo $this->element('Backend.Layout/admin/navbar_tasks'); ?>

            <!-- User Account Menu -->
            <?php echo $this->element('Backend.Layout/admin/navbar_user'); ?>

            <!-- Control Sidebar Toggle Button
            <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
            -->
        </ul>
    </div>
</nav>