<?php
foreach (['form', 'boxes', 'tables', 'components', 'tabs', 'icons', 'vectormap'] as $section) {
    $this->Toolbar->addLink($section, ['controller' => 'Design', 'action' => 'index', '?' => ['section' => $section]]);
}
$this->assign('heading', __d('admin', 'Design {0}', $this->request->getQuery('section') ?: 'Index'));
?>
<style>
    .design-index .section-header {
        padding: 5px 15px;
        margin: 2em 0 1em 0;
        color: #FFF;
        background-color: #000;
        border-bottom: 5px solid #ff9900;
        font-size: 1.6em;
    }
</style>
<div class="design-index index">
    <?= $this->fetch('content'); ?>
</div>
