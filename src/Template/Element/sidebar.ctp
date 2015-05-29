<?php
/**
 * Backend Sidebar
 *
 * Check Admin/AppController in application dir and plugin paths
 * if class exists and has a public static 'backendMenu' method.
 *
 * @TODO Refactor with Cell
 */
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Core\Plugin;

$menu = [];
$menuOrder = (Configure::read('Backend.Menu.order')) ?: [];
$loadedPlugins = Plugin::loaded();


/**
 * Resolve menu items from app or plugin
 * @param $val string Plugin name or NULL
 */
$menuResolver = function ($val) use (&$menu) {
    // get AppController classname for app or plugin
    $plugin = $val;
    $className = ($plugin) ? $plugin . '.' . 'App' : 'App';

    $class = \Cake\Core\App::className($className, 'Controller/Admin', 'Controller');
    if (!$class) {
        return;
    }

    // call static 'AppController::backendMenu()' method
    // and merge into $menu
    $callable = [$class, 'backendMenu'];
    if (is_callable($callable)) {
        $_menu = call_user_func($callable);
        //array_push($menu, (array) $_menu);
        $menu = array_merge($menu, (array) $_menu);
    }
};


$requestedPlugin = (isset($this->request->params['plugin'])) ? $this->request->params['plugin'] : null;
$menuItemBuilder = function ($item, $childBuilder, $level = 0) use ($requestedPlugin) {
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

    //if ($level == 0 && $plugin == $requestedPlugin) {
    //    $item = $this->Html->addClass($item, 'active');
    //}
    if (Router::normalize($url) == '/' . $this->request->url) {
        $item = $this->Html->addClass($item, 'active');
    }

    if (!empty($children)) {
        $subMenu = '<div class="menu">';
        foreach ($children as $child) {
            $child['plugin'] = $plugin;
            $subMenu .= $childBuilder($child, $childBuilder, $level+1);
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

// resolve app menus
$menuResolver(null);
// resolve plugin menus
array_walk($loadedPlugins, $menuResolver);

/**
 * Build nested html structure for backend menu items
 * @param $menu
 * @return string
 */
$menuBuilder = function ($menu) use ($menuItemBuilder, $menuOrder) {

    if (!empty($menuOrder)) {
        $menu = array_intersect_key($menu, array_flip($menuOrder));
    } else {
        ksort($menu, SORT_ASC);
    }

    $html = "";
    foreach ($menu as $item) {
        $html .= $menuItemBuilder($item, $menuItemBuilder);
    }
    return $html;
};

//debug($menu);
?>
<div class="be-sidebar ui left vertical visible overlay sidebar pointing inverted menu">
    <h3 class="ui header item">
        Administration
    </h3>
    <!--
    Search
    <div class="item search">
        <div class="ui input">
            <input placeholder="Search..." type="text">
        </div>
    </div>
     -->

    <!-- Menu -->
    <?php
    echo $menuBuilder($menu);
    ?>

    <?php //echo $this->fetch('backend-sidebar'); ?>
</div>