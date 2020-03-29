<?php
/**
 * Default pagination template element
 */
$counter = $counter ?? []; // Paginator::counter options
$numbers = $numbers ?? []; // Paginator::number options
return;
?>
<nav class="nav-pagination" style="margin: 0.5em 0 1em 0">
    <ul class="pagination pagination-counter">
        <li><span><?= $this->Paginator->counter('pages') ?></span></li>
    </ul>

    <ul class="pagination pagination-menu pull-right">
        <?= $this->Paginator->prev(__d('backend', 'previous')) ?>
        <?= $this->Paginator->numbers($numbers) ?>
        <?= $this->Paginator->next(__d('backend', 'next')) ?>
    </ul>
    <div class="clearfix"></div>
</nav>