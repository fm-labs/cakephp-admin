<?php
/**
 * AdminLTE content header
 *
 * Heading text is read from view vars 'heading'
 * Fallback to view var 'title'
 * Optionally use 'heading_small' for secondary heading
 *
 * Set '_no_header' to TRUE or set 'heading' to FALSE to disable heading rendering
 * Set '_no_breadcrumbs' to TRUE or set 'breadcrumbs' to FALSE to disable breadcrumb rendering
 */
if ($this->get('_no_header')) {
    return;
}

$this->loadHelpers('Breadcrumbs');
$breadcrumbs = $this->Breadcrumbs->render(['class' => 'breadcrumb']);
?>
<section class="content-header">
    <h1>
        <?= $this->fetch('heading', $this->fetch('title')); ?>&nbsp;
        <?php if ($this->fetch('heading_small')): ?>
            <small><?= $this->fetch('heading_small'); ?></small>
        <?php endif; ?>
    </h1>

    <?php if (!$this->get('_no_breadcrumbs')): ?>
    <?= $breadcrumbs; ?>
    <?php endif; ?>
</section>