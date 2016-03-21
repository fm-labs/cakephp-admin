<?php
use Backend\Lib\BackendNav;

?>

<div id="master-container" class="container-fluid">
    <div id="master-tabs">

        <!-- Nav tabs -->
        <ul id="master-tab-list" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div id="master-tab-content" class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                You dont have any windows open<br /><br />

                <?= $this->Html->link(
                    'Open Systeminfo',
                    ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                    ['title' => 'Systeminfo']
                ); ?>
            </div>
        </div>
    </div>
</div>