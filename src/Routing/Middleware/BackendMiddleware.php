<?php
namespace Backend\Routing\Middleware;

use Backend\Backend;
use Backend\BackendPluginInterface;
use Banana\Application;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Applies routing rules to the request and creates the controller
 * instance if possible.
 */
class BackendMiddleware
{

    protected $_iframe = false;

    public function __construct(Application $app)
    {
        $this->_app = $app;
        $this->_backend = new Backend();

        // register url filter for persistent URL parameters.
        // injects 'lang' param on url creation
        Router::addUrlFilter(function ($params, \Cake\Network\Request $request) {
            $params['iframe'] = $this->_iframe;

            return $params;
        });
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next The next middleware to call.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $params = $request->getServerParams();

        $urlPrefix = '/' . trim(Backend::$urlPrefix, '/') . '/';
        if (isset($params['REQUEST_URI']) && preg_match('/^' . preg_quote($urlPrefix, '/') . '/', $params['REQUEST_URI'])) {
            // lazy backend plugin initialization
            foreach ($this->_app->plugins()->loaded() as $pluginName) {
                $instance = $this->_app->plugins()->get($pluginName);
                if ($instance instanceof BackendPluginInterface) {
                    try {
                        // bootstrap
                        call_user_func([$instance, 'backendBootstrap'], $this->_backend);

                        // routes: create admin scope for each plugin
                        Router::scope($urlPrefix . Inflector::underscore($pluginName), [
                            'plugin' => $pluginName,
                            'prefix' => 'admin',
                            '_namePrefix' => sprintf("%s:admin:", Inflector::underscore($pluginName))
                        ], [$instance, 'backendRoutes']);
                    } catch (\Exception $ex) {
                        Log::error("Backend plugin loading failed: $pluginName: " . $ex->getMessage());
                    }
                }
            }

            // iframe detection
            $query = $request->getQueryParams();
            if (isset($query['iframe'])) {
                $this->_iframe = (bool)$query['iframe'];
            }
        }

        return $next($request, $response);
    }
}
