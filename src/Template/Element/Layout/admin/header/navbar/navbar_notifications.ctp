<li class="dropdown notifications-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning">10</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 10 notifications</li>
        <li>
            <!-- Inner Menu: contains the notifications -->
            <ul class="menu">
                <?php for ($i = 0; $i < 3; $i++): ?>
                <li><!-- start notification -->
                    <a href="#">
                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                </li>
                <?php endfor; ?>
                <!-- end notification -->
            </ul>
        </li>
        <li class="footer"><a href="#">View all</a></li>
    </ul>
</li>