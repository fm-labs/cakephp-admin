<?php
$menu = $menu ?? [];
$this->loadHelper('Bootstrap.Button')
?>
<div class="nav flex-column">
    <?php foreach($menu as $item):
        $menuId = uniqid('menu');
        $active = false;
        $linkClass = 'nav-link py-1';
        $collapseClass = 'collapse';
        $ariaExpanded = "false";

    ?>
    <div class="nav-item">
        <?php if (empty($item['children'] ?? [])): ?>
            <?php
            if (\Cake\Routing\Router::normalize($item['url']) === \Cake\Routing\Router::normalize($this->getRequest()->getPath())) {
                $active = true;
                $linkClass .= ' active';
            }

            $linkOptions = [
                'class' => $linkClass,
            ];
            $linkOptions = \Cake\Utility\Hash::merge($linkOptions, $item['attr'] ?? []);
            echo $this->Html->link($item['title'], $item['url'], $linkOptions);
            ?>
        <?php else: ?>
            <?php
            if (is_array($item['url'])) {
                $plugin = $item['url']['plugin'] ?? null;
                if ($plugin === $this->getRequest()->getParam('plugin')) {
                    $active = true;
                    $linkClass .= ' active';
                    $collapseClass .= ' show';
                    $ariaExpanded = "true";
                }
            }

            $linkOptions = [
                'class' => $linkClass,
                //'class' => 'btn btn-toggle d-inline-flex align-items-center rounded border-0',
                'data-bs-toggle' => 'collapse',
                'data-bs-target' => '#' . $menuId,
                'aria-controls' => $menuId,
                'aria-expanded' => $active
            ];
            $linkOptions = \Cake\Utility\Hash::merge($linkOptions, $item['attr'] ?? []);
            echo $this->Html->link($item['title'], $item['url'], $linkOptions);
            ?>
            <div class="<?= $collapseClass ?>" id="<?= $menuId ?>" style="width: 99%;">
                <ul class="list-unstyled fw-normal px-0 py-2 m-0 bg-white border-bottom">
                    <?php foreach($item['children'] as $child): ?>
                        <?php
                        $title = $child['title'];
                        $url = $child['url'];
                        $options = $child['attr'];
                        //unset($options['url']);
                        //unset($options['children']);
                        //$options['class'] = 'link-dark d-inline-flex text-decoration-none rounded'
                        $options['class'] = 'link-dark d-inline-flex text-decoration-none';

                        if (\Cake\Routing\Router::normalize($child['url']) === \Cake\Routing\Router::normalize($this->getRequest()->getPath())) {
                            $options['class'] .= ' active';
                        }
                        ?>
                        <li class="ps-3 pe-2 py-0">
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
    </div>
    <?php endforeach ?>
</div>