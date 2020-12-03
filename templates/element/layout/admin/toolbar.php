<?php
/**
 * @var \Cake\View\View $this
 */

if (!$this->helpers()->has('Toolbar')) {
    return (\Cake\Core\Configure::read('debug')) ? '[Toolbar Helper not loaded]' : '';
}
?>
<nav class="toolbar">
    <?php echo $this->Toolbar->render(); ?>
</nav>
