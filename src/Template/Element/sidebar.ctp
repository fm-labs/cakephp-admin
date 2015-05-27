<?php
/**
 * Backend Sidebar
 *
 * Check Admin/AppController in application dir and plugin paths
 * if class exists and has a public static 'backendMenu' method.
 */

$menu = [];
$menuResolver = function ($val) use (&$menu) {
    $plugin = $val;
    $className = ($plugin) ? $plugin . '.' . 'App' : 'App';

    $class = \Cake\Core\App::className($className, 'Controller/Admin', 'Controller');
    if (!$class) {
        return;
    }

    $callable = [$class, 'backendMenu'];
    if (is_callable($callable)) {
        $_menu = call_user_func($callable);
        //array_push($menu, (array) $_menu);
        $menu = array_merge_recursive($menu, (array) $_menu);
    }
};

// resolve app menus
$menuResolver(null);
// resolve plugin menus
$loadedPlugins = \Cake\Core\Plugin::loaded();
array_walk($loadedPlugins, $menuResolver);

$requestedPlugin = (isset($this->request->params['plugin'])) ? $this->request->params['plugin'] : null;
$menuItemBuilder = function ($item, $childBuilder) use ($requestedPlugin) {
    $_default = ['plugin' => null, 'url' => null, 'title' => null, 'icon' => null, '_children' => []];
    $item = array_merge($_default, $item);

    $plugin = $item['plugin'];
    unset($item['plugin']);

    $url = $item['url'];
    unset($item['url']);

    $children = $item['_children'];
    unset($item['_children']);

    //$icon = $item['icon'];
    //unset($item['icon']);

    $title = $item['title'];

    if ($plugin == $requestedPlugin) {
        $item = $this->Html->addClass($item, 'active');
    }

    if (!empty($children)) {
        $subMenu = '<div class="menu">';
        foreach ($children as $child) {
            $child['plugin'] = $plugin;
            $subMenu .= $childBuilder($child, $childBuilder);
        }
        $subMenu .= '</div>';

        $icon = ($item['icon']) ? sprintf('<i class="%s icon"></i>', $item['icon']) : '';
        unset($item['icon']);
        //$title = $title . " (" . count($children) . ")";

        $title = $this->Ui->link($title, $url, $item);

        return $this->Html->div('item', $title . $icon . $subMenu);
    } else {
        $item = $this->Html->addClass($item, 'item');
        return $this->Ui->link($title, $url, $item);
    }

};

$menuBuilder = function ($menu) use ($menuItemBuilder) {
    $html = "";
    foreach ($menu as $item) {
        $html .= $menuItemBuilder($item, $menuItemBuilder);
    }
    return $html;
};


//debug($menu);
?>
<div class="be-sidebar ui left vertical visible overlay sidebar inverted menu">
    <h3 class="ui header item">
        Backend
    </h3>
    <!-- Search -->
    <div class="item">
        <div class="ui input"><input placeholder="Search..." type="text"></div>
    </div>

    <!-- Menu -->
    <?php
    echo $menuBuilder($menu);
    ?>

    <?php //echo $this->fetch('backend-sidebar'); ?>
</div>