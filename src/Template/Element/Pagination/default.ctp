<div class="paginator">
    <div class="ui pagination menu">
        <?= $this->Paginator->prev(__('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next')) ?>

        <div class="item">
            <?= $this->Paginator->counter() ?>
        </div>
    </div>
</div>