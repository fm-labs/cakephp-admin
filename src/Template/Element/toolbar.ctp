<div class="ui large opaque borderless menu navbar grid sticky">

    <div class="item">
        <?= $this->Ui->link(
            $this->get('be_title'),
            $this->get('be_dashboard_url'),
            ['class' => 'ui primary button', 'icon' => 'home']
        ); ?>
    </div>

    <div class="item">
        <?= $this->Ui->link(
            'Secondary Button',
            ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
            ['class' => 'ui secondary button', 'icon' => 'cubes']
        ); ?>
    </div>

    <div class="item">
        <?= $this->Ui->link(
            'Button',
            ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
            ['class' => 'ui button', 'icon' => 'cubes']
        ); ?>
    </div>

    <div class="item">
        <?= $this->Ui->link(
            'Link',
            ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
            ['class' => '', 'icon' => 'cubes']
        ); ?>
    </div>
</div>