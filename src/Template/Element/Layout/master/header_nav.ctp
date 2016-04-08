<?php
use Backend\Lib\BackendNav;

if (!$this->request->session()->check('Backend.User')) return false;
//$this->Html->css('Backend.navigation', ['block' => true]);
?>
<div class="container-fluid">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#master-navbar" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <?= $this->Html->link(
            $this->get('be_title'),
            ['_name' => 'backend:admin:master'],
            ['class' => 'navbar-brand', 'target' => '_top']
        ); ?>
    </div>


    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="master-navbar">


        <?php
        $backendNavMenu = BackendNav::getMenu();
        echo $this->Ui->menu($backendNavMenu, ['class' => 'nav navbar-nav'], ['class' => 'dropdown-menu']);
        ?>

        <!--
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
        -->

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?= $this->Ui->icon('user'); ?>
                    <?= __('{0}', $this->request->session()->read('Backend.User.name')); ?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <?= $this->Ui->link('Profile',
                            ['_name' => 'backend:admin:auth:user']
                        ); ?>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <?= $this->Ui->link(
                            __('Logout'),
                            ['_name' => 'backend:admin:auth:logout'],
                            ['class' => 'link-master']
                        );
                        ?>
                    </li>
                </ul>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->

</div>