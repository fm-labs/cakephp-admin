<!--
<div class="ui grid">
    <div class="computer tablet only row" style="padding: 0;">
    -->
        <div class="ui opaque secondary menu navbar grid">

            <!--
            <?= $this->Ui->link(
                $this->get('be_title'),
                ['_name' => 'admin:index'],
                ['class' => 'item', 'data-icon' => 'home']
            ); ?>

            <?= $this->Ui->link(
                'Admin',
                ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'index'],
                ['class' => 'item', 'data-icon' => 'cubes']
            ); ?>
            -->

            <div class="right menu">

                <!--
                <div class="item">
                    <div class="ui icon mini input">
                        <input placeholder="Filter..." type="text">
                        <i class="search link icon"></i>
                    </div>
                </div>
                -->

                <?= $this->Ui->link(
                    'Reset',
                    '#',
                    ['class' => 'item', 'data-icon' => 'comment']
                ); ?>

                <?= $this->Ui->link(
                    'Submit',
                    '#',
                    ['class' => 'item', 'data-icon' => 'play']
                ); ?>

                <?= $this->Ui->link(
                    'Archive Item',
                    '#',
                    ['class' => 'item']);
                ?>
                <div class="ui divider"></div>
                <?= $this->Ui->link(
                    __d('admin','Publish Item'),
                    '#',
                    ['class' => 'item']);
                ?>

                <!--
                <div class="ui dropdown item">
                    <i class="setting icon"></i>
                    More
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <?= $this->Ui->link(
                            'Archive Item',
                            '#',
                            ['class' => 'item']);
                        ?>
                        <div class="ui divider"></div>
                        <?= $this->Ui->link(
                            __d('admin','Publish Item'),
                            '#',
                            ['class' => 'item']);
                        ?>
                    </div>
                </div>
                -->
            </div>

            <!--
                </div>
            </div>
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