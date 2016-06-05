<?php $this->assign('title', __('Administration')); ?>
<div id="master-container">

    <!-- Master Tabs -->
    <div id="master-tabs">

        <!-- Tab list -->
        <ul id="master-tab-list" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active" data-tab-id="tabhome">
                <a href="#tabhome" aria-controls="home" role="tab" data-toggle="tab" data-tab-id="tabhome" data-url="<?= $this->Html->Url->build(['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index']); ?>"><i class="fa fa-list"></i></a>
            </li>
        </ul>

        <!-- Tab content frames -->
        <div id="master-tab-content" class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tabhome">
            </div>
        </div>
    </div>
</div>