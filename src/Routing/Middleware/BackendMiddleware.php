<?php
namespace Backend\Routing\Middleware;

use Backend\Backend;
use Banana\Banana;
use Cake\Routing\Exception\RedirectException;
use Cake\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Applies routing rules to the request and creates the controller
 * instance if possible.
 */
class BackendMiddleware
{

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
            Banana::getInstance()->runBackend();
            //Backend::run();
        }

        return $next($request, $response);
    }
}
