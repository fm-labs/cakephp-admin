<?php
$menu = $menu ?? [];
$this->loadHelper('Bootstrap.Button')
?>
<ul class="list-unstyled ps-0">
    <?php foreach($menu as $item):
        $menuId = uniqid('menu'); ?>
    <li class="mb-1">
        <?= $this->Button->create($item['title'], [
                'class' => 'btn btn-toggle d-inline-flex align-items-center rounded border-0',
                'data-bs-toggle' => 'collapse',
                'data-bs-target' => '#' . $menuId,
                'aria-controls' => $menuId,
                'aria-expanded' => "false"
        ]) ?>

        <?php if (!empty($item['children'] ?? [])): ?>
        <div class="collapse" id="<?= $menuId ?>" style="">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <?php foreach($item['children'] as $child): ?>
                    <?php
                    $title = $child['title'];
                    $url = $child['url'];
                    $options = $child['attr'];
                    //unset($options['url']);
                    //unset($options['children']);
                    $options['class'] = 'link-dark d-inline-flex text-decoration-none rounded'
                    ?>
                    <li>
                        <?= $this->Html->link(
                                $title,
                                $url,
                                $options
                        ); ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        <?php endif; ?>
    </li>
    <?php endforeach ?>
</ul>