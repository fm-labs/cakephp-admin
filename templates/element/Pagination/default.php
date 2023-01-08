<?php
/**
 * Default pagination template element
 */
$counterFormat = $counterFormat ?? 'range';
$counter = $counter ?? []; // Paginator::counter options
$numbers = $numbers ?? []; // Paginator::number options

$this->Paginator->templater()->add([
//    'options' => [],
//    'templates' => [
//        'nextActive' => '<li class="next"><a rel="next" href="{{url}}">{{text}}</a></li>',
//        'nextDisabled' => '<li class="next disabled"><a href="" onclick="return false;">{{text}}</a></li>',
//        'prevActive' => '<li class="prev"><a rel="prev" href="{{url}}">{{text}}</a></li>',
//        'prevDisabled' => '<li class="prev disabled"><a href="" onclick="return false;">{{text}}</a></li>',
//        'counterRange' => '{{start}} - {{end}} of {{count}}',
//        'counterPages' => '{{page}} of {{pages}}',
//        'first' => '<li class="first"><a href="{{url}}">{{text}}</a></li>',
//        'last' => '<li class="last"><a href="{{url}}">{{text}}</a></li>',
        'nextActive' => '<a rel="next" class="btn btn-sm btn-outline-secondary" href="{{url}}">{{text}}</a>',
        'nextDisabled' => '<a disabled="true" rel="next" class="btn btn-sm btn-outline-secondary" href="" onclick="return false;">{{text}}</a>',
        'prevActive' => '<a rel="prev" class="btn btn-sm btn-outline-secondary" href="{{url}}">{{text}}</a>',
        'prevDisabled' => '<a disabled="true" rel="prev" class="btn btn-sm btn-outline-secondary" href="" onclick="return false;">{{text}}</a>',
        'first' => '<a class="btn btn-sm btn-outline-secondary" href="{{url}}">{{text}}</a>',
        'last' => '<a class="btn btn-sm btn-outline-secondary" href="{{url}}">{{text}}</a>',
        'number' => '<a class="btn btn-sm btn-outline-secondary" href="{{url}}">{{text}}</a>',
        'current' => '<a class="btn btn-sm btn-primary active" href="{{url}}">{{text}}</a>',
//        'current' => '<li class="active"><a href="">{{text}}</a></li>',
//        'ellipsis' => '<li class="ellipsis">&hellip;</li>',
//        'sort' => '<a href="{{url}}">{{text}}</a>',
//        'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
//        'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
//        'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
//        'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
//    ],
]);

?>
<nav class="nav-pagination d-flex flex-row justify-content-between">
    <div class="pagination-counter">
        <span class="btn btn-outline-secondary btn-sm"><?= $this->Paginator->counter($counterFormat, $counter) ?></span>
    </div>

    <div class="btn-group pagination-nav" role="group" aria-label="Pagination">
        <?= $this->Paginator->prev(__d('admin', 'previous')) ?>
        <?= $this->Paginator->numbers($numbers) ?>
        <?= $this->Paginator->next(__d('admin', 'next')) ?>
    </div>
</nav>