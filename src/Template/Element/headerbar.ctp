<div class="ui grid">
    <div class="computer tablet only row" style="padding: 0;">
        <div class="ui fixed menu navbar grid">
            <!--
            <?= $this->Ui->link('', '#', ['id' => 'backend-admin-sidebar-toggle', 'class' => 'item', 'icon' => 'content']); ?>
            -->
            <?= $this->Ui->link(
                $this->get('be_title'),
                $this->get('be_dashboard_url'),
                ['class' => 'item', 'icon' => 'rocket']
            ); ?>

            <?= $this->Ui->link(
                'Backend',
                ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
                ['class' => 'item', 'icon' => 'cubes']
            ); ?>

            <div class="right menu">

                <!--
                <?= $this->Ui->link(
                    'Messages',
                    '/backend/admin/Messages',
                    ['class' => 'item', 'icon' => 'comment']
                ); ?>
                -->

                <?php if ($this->request->session()->check('Auth.User')): ?>
                <div class="ui dropdown item">
                    <i class="user icon"></i>
                    <?= __('{0}', $this->request->session()->read('Auth.User.name')); ?>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <!--
                        <?= $this->Ui->link('Profile', '/backend/admin/Auth/user', ['class' => 'item']); ?>
                        <div class="ui divider"></div>
                        -->
                        <?= $this->Ui->link(
                            __('Logout'),
                            $this->get('be_auth_logout_url'),
                            ['class' => 'item']);
                        ?>
                    </div>
                </div>
                <?php else: ?>
                    <!-- @TODO Insert 'unauthenticated' notice -->
                <?php endif; ?>
            </div>

        </div>
    </div>
    <!--
    <div class="mobile only row">
        <div class="ui fixed inverted navbar menu">
            <a href="" class="brand item">Project Name</a>
            <div class="right menu open">
                <a href="" class="menu item">
                    <i class="reorder icon"></i>
                </a>
            </div>
        </div>
        <div class="ui vertical navbar menu">
            <a href="" class="active item">Home</a>
            <a href="" class="item">About</a>
            <a href="" class="item">Contact</a>
            <div class="ui item">
                <div class="text">Dropdown</div>
                <div class="menu">
                    <a class="item">Action</a>
                    <a class="item">Another action</a>
                    <a class="item">Something else here</a>
                    <a class="ui aider"></a>
                    <a class="item">Seperated link</a>
                    <a class="item">One more seperated link</a>
                </div>
            </div>
            <div class="menu">
                <a href="" class="active item">Default</a>
                <a href="" class="item">Static top</a>
                <a href="" class="item">Fixed top</a>
            </div>
        </div>
    </div>
    -->
</div>