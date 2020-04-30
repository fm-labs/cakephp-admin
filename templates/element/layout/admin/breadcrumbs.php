<?php
/**
 * @deprecated Use BreadcrumbHelper or Admin's Layout/BreadcrumbHelper instead
 */
return;
?>
<?= $this->Html->getCrumbList([
    'class' => 'breadcrumb'
], ['text' => $this->get('be_title')/*, 'url' => ['_name' => 'admin:admin:dashboard']*/]);
