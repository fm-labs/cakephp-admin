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
use Cake\Utility\Inflector;

$menu = [];
$menuOrder = (Configure::read('Backend.sidebar')) ?: [];
$loadedPlugins = Plugin::loaded();
$domId = uniqid('beSidebar');

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
$menuItemBuilder = function ($item, $childBuilder, $level = 0, $trail = false) use ($requestedPlugin) {

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
    $activePlugin = $trail = ($plugin == $requestedPlugin) ? true : false;
    $activeRoute = false;
    if (Router::normalize($url) == '/' . $this->request->url) {
        $activeRoute = true;
        //$item = $this->Html->addClass($item, 'active');
    }

    if (!empty($children)) {
        $subMenu = '<div class="menu">';
        foreach ($children as $child) {
            $child['plugin'] = $plugin;
            $subMenu .= $childBuilder($child, $childBuilder, $level+1, $trail);
        }
        $subMenu .= '</div>';

        //$icon = ($item['icon']) ? sprintf('<i class="%s icon"></i>', $item['icon']) : '';
        //unset($item['icon']);
        //$title = $title . " (" . count($children) . ")";

        $item['escape'] = false;
        $item['data-plugin'] = $plugin;

        $title = '<span>' . h($title) . '</span>';
        $link = $this->Ui->link($title, $url, $item);

        $class = 'item';
        $class .= ($trail) ? ' trail' : '';
        $class .= ($plugin) ? ' plugin-' . Inflector::underscore($plugin) : ' app';
        return $this->Html->div($class, $link . $subMenu);
    } else {
        if ($activeRoute) {
            $item = $this->Html->addClass($item, 'active');
        }
        elseif ($trail) {
            $item = $this->Html->addClass($item, 'trail');
        }
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
        $orderedMenu = [];
        array_walk($menuOrder, function ($val) use ($menu, &$orderedMenu) {
            if (isset($menu[$val]) && is_array($menu[$val]) && !empty($menu[$val])) {
                $orderedMenu[$val] = $menu[$val];
            }
        });
        $menu = $orderedMenu;
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
<div id="<?= $domId; ?>" class="be-sidebar ui left vertical visible overlay sidebar pointing inverted opaque menu">

    <div class="be-sidebar-toggle item">
        <a href="#">
            <i class="ui angle double left icon"></i>
            <span>hide menu</span>
        </a>
    </div>

    <div class="item">
        <?= $this->Ui->link(
            $this->Html->tag('span', $this->get('be_title')),
            $this->get('be_dashboard_url'),
            ['icon' => 'inverted blue home', 'escape' => false]
        ); ?>
        <!--
        <a href="#">
            <i class="be-sidebar-toggle ui content icon"></i>
            <span>Menu</span>
        </a>
        -->
    </div>
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
<?php $this->append('script-bottom'); ?>
<script>
$(document).ready(function() {

    var storage;
    if (!!window.localStorage) {
        //console.log("Supports localStorage");
        storage = localStorage;
    } else if (!!window.sessionStorage) {
        //console.log("Supports sessionStorage")
        storage = sessionStorage;
    } else {
        // @TODO fallback with cookie storage
    }

    $('#<?= $domId; ?> .be-sidebar-toggle').click(function(e) {
       //console.log(e);

       var $sb = $('#<?= $domId; ?>').closest('.be-sidebar');
       $('body').toggleClass('be-sidebar-small');
       $('#<?= $domId; ?> .be-sidebar-toggle i.icon').toggleClass('left right');

       if (storage !== "undefined") {
           //console.log('Current state: ' + storage.getItem('beSidebarCollapsed'));
           var state = ($('body').hasClass('be-sidebar-small') === true) ? 'true' : 'false';

           //console.log("Updating sidebar state: " + state);
           storage.setItem('beSidebarCollapsed', state);
       }
    });

    //console.log('Current state: ' + storage.getItem('beSidebarCollapsed'));
    if (storage !== "undefined" && storage.getItem('beSidebarCollapsed') === 'true') {
        //console.log("sidebar should be collapsed");
        $('body').addClass('be-sidebar-small');
        $('#<?= $domId; ?> .be-sidebar-toggle i.icon').toggleClass('left right');
    }
});
</script>
<?php $this->end(); ?>