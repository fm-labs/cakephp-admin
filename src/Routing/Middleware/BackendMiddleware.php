<?php
namespace Backend\Routing\Middleware;

use Backend\Backend;
use Backend\BackendPluginInterface;
use Banana\Application;
use Banana\Banana;
use Cake\Routing\Exception\RedirectException;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Applies routing rules to the request and creates the controller
 * instance if possible.
 */
class BackendMiddleware
{

    public function __construct(Application $app)
    {
        $this->_app = $app;
        $this->_backend = new Backend();
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

        if (isset($params['REQUEST_URI']) && preg_match('/^' . preg_quote(rtrim(Backend::$urlPrefix, '/') . '/', '/') . '/', $params['REQUEST_URI'])) {

            // load backend routes on-the-fly
            foreach ($this->_app->plugins()->loaded() as $name) {
                $instance = $this->_app->plugins()->get($name);
                if ($instance instanceof BackendPluginInterface) {
                    $instance->backendBootstrap($this->_backend);

                    Router::scope('/admin/' . Inflector::underscore($name), [
                        'plugin' => $name,
                        'prefix' => 'admin',
                        '_namePrefix' => sprintf("%s:admin:", Inflector::underscore($name))
                    ], [$instance, 'backendRoutes']);
                }
            }
        }

        return $next($request, $response);
    }
}
