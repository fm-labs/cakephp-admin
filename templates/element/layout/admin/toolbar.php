<?php
/**
 * @var \Cake\View\View $this
 */

if (!$this->helpers()->has('Toolbar')) {
    return (\Cake\Core\Configure::read('debug')) ? '[Toolbar Helper not loaded]' : '';
}
?>
<nav class="toolbar">
    <div class="container-fluid">
        <?= $this->Toolbar->render(); ?>
    </div>
</nav>
