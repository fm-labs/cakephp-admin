<?php
/**
 * Default pagination template element
 */
$counter = (isset($counter)) ? (array) $counter : []; // Paginator::counter options
$numbers = (isset($numbers)) ? (array) $numbers : []; // Paginator::number options
?>
<nav>
    <ul class="pagination">
        <?= $this->Paginator->counter($counter) ?>
    </ul>

    <ul class="pagination pull-right">
        <?= $this->Paginator->prev(__d('backend','previous')) ?>
        <?= $this->Paginator->numbers($numbers) ?>
        <?= $this->Paginator->next(__d('backend','next')) ?>
    </ul>
</nav>