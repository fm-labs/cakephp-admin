<?php $this->loadHelper('Time'); ?>
<li class="dropdown user user-menu">
    <!-- Menu Toggle Button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <!-- The user image in the navbar-->
        <?php //echo $this->Html->image('/admin/adminlte/dist/img/user2-160x160.jpg', ['class' => 'user-image', 'alt' => 'User Image']); ?>
        <i class="fa fa-user"></i>
        <!-- hidden-xs hides the username on small devices so only the image appears. -->
        <span class="hidden-xs"><?= h($this->request->getSession()->read('Admin.User.name')); ?></span>
    </a>
    <ul class="dropdown-menu">
        <!-- The user image in the menu -->
        <li class="user-header">
            <?php //echo $this->Html->image('/admin/adminlte/dist/img/user2-160x160.jpg', ['class' => 'img-circle', 'alt' => 'User Image']); ?>
            <i class="fa fa-user fa-5x"></i>
            <p class="user-name">
                <?= h($this->request->getSession()->read('Admin.User.name')); ?>
            </p>
            <p class="user-info">
                <small>Member since <?= $this->Time->nice($this->request->getSession()->read('Admin.User.created')); ?></small>
            </p>
        </li>
        <!-- Menu Body (unused yet)-->
        <li class="user-body" style="display: none">
            <div class="row">
                <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                </div>
            </div>
            <!-- /.row -->
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <?= $this->Html->link(
                    __d('admin', 'Profile'),
                    ['_name' => 'admin:system:user:profile'],
                    ['class' => 'btn btn-outline-secondary btn-flat']
                ); ?>
            </div>
            <div class="pull-right">
                <?= $this->Html->link(
                    __d('admin', 'Sign out'),
                    ['_name' => 'admin:system:user:logout'],
                    ['class' => 'btn btn-outline-secondary btn-flat']
                ); ?>
            </div>
            <div class="clearfix"></div>
        </li>
    </ul>
</li>