<div class="user-panel">
    <div class="pull-left image">
        <?php //echo $this->Html->image('/admin/adminlte/dist/img/user2-160x160.jp', ['class' => 'img-circle', 'alt' => 'User Image']); ?>
        <i class="fa fa-3x fa-user" style="color: #FFF;"></i>
    </div>
    <div class="pull-left info">
        <p><?= h($this->request->getSession()->read('Admin.User.name')); ?></p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
</div>