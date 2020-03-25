<div id="<?= $menu->id; ?>" class="ui basic mini buttons <?= $menu->class; ?>">
<?php foreach ($menu as $item) : ?>
    <?php if ($item->children->count() < 1) : ?>
        <div class="ui button">
        <?php
        //$item['attr'] = $this->Html->addClass((array) $item['attr'], 'item');
        echo $this->Ui->link(
            $item['title'],
            $item['url'],
            $item['attr']
        ); ?>
        </div>
    <?php else: ?>
        <div class="ui floating dropdown icon button">
            <i class="dropdown icon"></i>
            <div class="menu">
            <?php foreach ($item->getChildren() as $child) : ?>
                <?php
                $child = $child->toArray();
                $child['attr'] = $this->Html->addClass((array) $child['attr'], 'item');
                echo $this->Ui->link(
                    $child['title'],
                    $child['url'],
                    $child['attr']
                ); ?>
            <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
</div>