<?php

namespace Admin\Ui\Layout\Sidebar;

use Admin\Ui\Layout\BaseLayoutElement;
use Cake\Core\Configure;

class SidebarMenu extends BaseLayoutElement
{
    protected $elementName = "Admin.layout/admin/sidebar/sidebar_menu";

    public function initialize(): void
    {
        $this->_View->loadHelper('Bootstrap.Menu');
    }

    public function data(): array
    {
        $primary = \Admin\Admin::getMenu('admin_primary')->toArray();
        $secondary = \Admin\Admin::getMenu('admin_secondary')->toArray();
        $system = \Admin\Admin::getMenu('admin_system')->toArray();
        return compact('primary', 'secondary', 'system');
    }

//    /**
//     * @param \Cake\View\View $view
//     * @return string
//     */
//    public function render(): string
//    {
//        $menuTemplates = [
//            'navLink' => '<a href="{{url}}"{{attrs}}><span>{{content}}</span></a>',
//            'navList' => '<ul class="{{class}}">{{title}}{{items}}</ul>',
//            'navListTitle' => '<li class="header">{{content}}</li>',
//            //'navListItem' => '<li role="presentation" class="{{class}}"{{attrs}}>{{link}}</li>',
//            //'navListItemSubmenu' => '<li role="presentation" class="{{class}}"{{attrs}}>{{link}}{{submenu}}</li>',
//            'navSubmenuList' => '<ul class="{{class}}">{{items}}</ul>',
//        ];
//        $menuClasses = [
//            'menu' => 'sidebar-menu',
//            'submenuItem' => 'treeview',
//            'submenu' => 'treeview-menu',
//            'item' => 'item',
//            'activeMenu' => 'menu-open',
//            'activeItem' => 'active',
//            'trailMenu' => 'menu-open',
//            'trailItem' => 'active',
//        ];
//
//        $html = "";
//        foreach (['admin_primary', 'admin_secondary'] as $menuId) {
//            //$menu = $this->get('admin.sidebar.menu');
//            $menu = \Admin\Admin::getMenu($menuId);
//            if (!$menu) {
//                if (Configure::read('debug')) {
//                    $html .= "Menu $menuId not found";
//                }
//                continue;
//            }
//
//            $html .= $this->_View->Menu->create([
//                'templates' => $menuTemplates,
//                'classes' => $menuClasses,
//                'items' => $menu->toArray(),
//            ])->render();
//        }
//
//        return $html;
//    }
}
