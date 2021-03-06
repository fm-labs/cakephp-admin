<?php
declare(strict_types=1);

namespace Admin;

use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Routing\Router;

class AdminPlugin extends \Admin\Core\BaseAdminPlugin implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        parent::bootstrap();
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Admin.Menu.build.admin_primary' => ['callable' => 'adminMenuBuildPrimary', 'priority' => 99 ],
            'Admin.Menu.build.admin_system' => ['callable' => 'adminMenuBuildSystem', 'priority' => 99 ],
            //'Controller.beforeRedirect' => 'controllerBeforeRedirect',
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event The event
     * @param mixed $url The redirect target url
     * @param \Cake\Http\Response $response The response object
     * @return \Cake\Http\Response|\Psr\Http\Message\MessageInterface
     */
    public function controllerBeforeRedirect(EventInterface $event, $url, \Cake\Http\Response $response)
    {
        // @todo Refactor using a View
        $html = <<<HTML
<html>
<head>
<meta charset="UTF-8" />
<title>{{TITLE}}</title>
<style>
body {
max-width: 500px;
margin: 0 auto;
padding: 10% 0;
}
.noscript {
font-weight: bold;
color: red;
}
</style>
</head>
<body>
<p>
    {{TITLE}}<br />
    <a href="{{URL}}">{{URL}}</a>
</p>

<noscript>
<p class="noscript">You need JavaScript enabled for automatic redirect to work. Please click the link above.</p>
</noscript>

<script>
var redirectTimeout = {{TIMEOUT}};
var redirectUrl = "{{URL}}";
console.log("Redirecting to " + redirectUrl + " in " + redirectTimeout/1000 + " seconds");
setTimeout(function() {
    console.log("Redirecting to " + redirectUrl);
    window.location.href=redirectUrl
}, redirectTimeout)
</script>
</body>
</html>
HTML;
        $replace = [
            '/\{\{TIMEOUT\}\}/' => 5000,
            '/\{\{TITLE\}\}/' => __('You will be redirected shortly ...'),
            '/\{\{URL\}\}/' => Router::url($url, true),
        ];

        $html = preg_replace(
            array_keys($replace),
            array_values($replace),
            $html
        );

        return $response
            ->withType('html')
            ->withStringBody($html);
    }

    /**
     * @param \Cake\Event\Event $event The event object
     * @param \Cupcake\Menu\MenuItemCollection $menu The menu
     * @return void
     */
    public function adminMenuBuildPrimary(Event $event, \Cupcake\Menu\MenuItemCollection $menu)
    {
        /*
        if (Configure::read('debug')) {
            $menu->addItem([
                'title' => __d('admin', 'Developer'),
                'data-icon' => 'paint-brush',
                'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index'],
                'children' => [
                    /*
                    'design' => [
                        'title' => __d('admin'  'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index'],
                    ],
                    'design_form' => [
                        'title' => __d('admin', 'Forms'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'form'],
                    ],
                    'design_boxes' => [
                        'title' => __d('admin', 'Boxes'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'boxes'],
                    ],
                    'design_tables' => [
                        'title' => __d('admin', 'Tables'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'tables'],
                    ],
                    'design_component' => [
                        'title' => __d('admin', 'Components'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'component'],
                    ],
                    'design_tabs' => [
                        'title' => __d('admin', 'Tabs'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'tabs'],
                    ]
                ],
            ]);
        }
        */
    }

    /**
     * @param \Cake\Event\Event $event The event object
     * @param \Cupcake\Menu\MenuItemCollection $menu The menu
     * @return void
     */
    public function adminMenuBuildSystem(Event $event, \Cupcake\Menu\MenuItemCollection $menu)
    {
        $items = [
            'system' => [
                'title' => 'Systeminfo',
                'url' => ['plugin' => 'Admin', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'info-circle',
            ],
            'logs' => [
                'title' => 'Logs',
                'url' => ['plugin' => 'Admin', 'controller' => 'Logs', 'action' => 'index'],
                'data-icon' => 'file-text-o',
            ],
            'cache' => [
                'title' => 'Cache',
                'url' => ['plugin' => 'Admin', 'controller' => 'Cache', 'action' => 'index'],
                'data-icon' => 'hourglass-o',
            ],
            'plugins' => [
                'title' => 'Plugins',
                'url' => ['plugin' => 'Admin', 'controller' => 'Plugins', 'action' => 'index'],
                'data-icon' => 'puzzle-piece',
            ],
            'design' => [
                'title' => __d('admin', 'Appearance'),
                'data-icon' => 'paint-brush',
                'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index'],
            ],
            'health' => [
                'title' => __d('admin', 'Health Status'),
                'data-icon' => 'heartbeat',
                'url' => ['plugin' => 'Admin', 'controller' => 'Health', 'action' => 'index'],
            ],
        ];
        $menu->addItems($items);
    }
}
