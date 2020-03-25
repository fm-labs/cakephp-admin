<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button
    -->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <!--
    <div class="navbar-custom-menu" style="float: left;">
        <ul class="nav navbar-nav">
            <li><?= $this->Html->link(__d('backend', 'Frontend'), '/', ['target' => '_blank']); ?></li>

        </ul>
    </div>
    -->

    <div class="navbar-menu-left">
        <?php echo $this->fetch('header_navbar_menu'); ?>
    </div>

    <div class="navbar-menu-right navbar-custom-menu">
        <ul class="nav navbar-nav">
            <?= $this->fetch('header_navbar_items'); ?>

            <!-- Control Sidebar Toggle Button
            <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
            -->
        </ul>
    </div>
</nav>
