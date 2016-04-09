<div id="master-container">

    <!-- Master Tabs -->
    <div id="master-tabs">

        <!-- Tab list -->
        <ul id="master-tab-list" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a>
            </li>
        </ul>

        <!-- Tab content frames -->
        <div id="master-tab-content" class="tab-content">
            <!-- Default frame -->
            <div role="tabpanel" class="tab-pane active" id="home">



                <div class="container-fluid">

                    You dont have any windows open<br /><br />

                    <?= $this->Html->link(
                        'Open Systeminfo in current window',
                        ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                        ['title' => 'Systeminfo']
                    ); ?>
                    <br /><br />
                    <?= $this->Html->link(
                        'Open Systeminfo in new window (tab)',
                        ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                        ['title' => 'Systeminfo', 'class' => 'link-frame']
                    ); ?>
                    <br /><br />
                    <?= $this->Html->link(
                        'Open Systeminfo in iframe modal',
                        ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                        ['title' => 'Systeminfo', 'class' => 'link-frame-modal']
                    ); ?>
                </div>



            </div>
        </div>
    </div>
</div>