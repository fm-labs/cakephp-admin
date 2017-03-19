<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <?= '' // $this->element('Backend.Layout/admin/sidebar_user'); ?>

        <!-- search form (Optional) -->
        <?= '' // $this->element('Backend.Layout/admin/sidebar_search'); ?>

        <!-- Sidebar Menu -->
        <?= $this->fetch('sidebar_menu', $this->element('Backend.Layout/admin/sidebar_menu')); ?>

    </section>
    <!-- /.sidebar -->
</aside>