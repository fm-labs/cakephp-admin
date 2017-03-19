<?php
if ($this->get('_no_header')) {
    return;
}

$this->loadHelpers('Breadcrumbs');
$breadcrumbs = $this->Breadcrumbs->render(['class' => 'breadcrumb']);
?>
<h1>
    <?= ($breadcrumbs) ?: $this->fetch('heading', $this->fetch('title')) ?>
</h1>