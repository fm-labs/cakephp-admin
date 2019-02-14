<?php
foreach (['form', 'boxes', 'tables', 'components'] as $section) {
    $this->Toolbar->addLink($section, ['action' => 'index', 'section' => $section]);
}
$this->assign('heading', __('Design {0}', ($this->request->query('section')) ?: 'Index'));
?>
<style>
    .design-index .section-header {
        padding: 5px;
        margin: 2em 0 1em 0;
        color: #222;
        background-color: #ff9900;
        border: 10px solid #000;
        font-size: 1.6em;
    }
</style>
<div class="design-index index">
    <?= $this->fetch('content'); ?>
</div>
