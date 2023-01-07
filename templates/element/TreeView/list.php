<ul>
    <?php foreach ($primary as $item): ?>
    <li>
        <div class="item">
            <a class="handle" href="#">X</a>
            <?= $this->Html->link($item->name, '#'); ?>
            <span class="actions"><i class="fa fa-gear"></i></span>
        </div>
        <?php if ($item->children): ?>
            <?php echo $this->element($element, ['items' => $item->children]); ?>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>