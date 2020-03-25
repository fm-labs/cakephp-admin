<nav role="navigation">
    <ul class="nav nav-pills nav-stacked">
        <?php foreach ($actions as $actionId => $action): ?>
        <li role="presentation">
            <?php $attrs = $action[2]; $attrs += ['type' => 'default']; ?>
            <?= $this->Button->link($action[0], $action[1], $attrs); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>