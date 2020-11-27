<?php
declare(strict_types=1);

namespace Admin\Routing\Middleware;

use Admin\Admin;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\RoutingApplicationInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Applies routing rules to the request and creates the controller
 * instance if possible.
 */
class AdminMiddleware implements MiddlewareInterface
{
//    protected $_iframe = false;

    /**
     * @inheritDoc
     */
    public function __construct(RoutingApplicationInterface $app)
    {
//        // register url filter for persistent URL parameters.
//        // injects 'iframe' param on url creation
//        Router::addUrlFilter(function ($params, \Cake\Http\ServerRequest $request) {
//            $params['iframe'] = $this->_iframe;
//
//            return $params;
//        });
    }

//    /**
//     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
//     * @param \Psr\Http\Message\ResponseInterface $response The response.
//     * @param callable $next The next middleware to call.
//     * @return \Psr\Http\Message\ResponseInterface A response.
//     */
//    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
//    {
//        $params = $request->getServerParams();
//
//        $urlPrefix = '/' . trim(Admin::$urlPrefix, '/') . '/';
//        if (isset($params['REQUEST_URI']) && preg_match('/^' . preg_quote($urlPrefix, '/') . '/', $params['REQUEST_URI'])) {
//            // iframe detection
//            $query = $request->getQueryParams();
//            if (isset($query['iframe'])) {
//                $this->_iframe = (bool)$query['iframe'];
//            }
//        }
//
//        return $next($request, $response);
//    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The next handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getAttribute('params', []);
        $prefix = $params['prefix'] ?? null;

        if (
            $prefix == 'Admin' ||
            preg_match('/^\/' . Admin::$urlPrefix . '/', $request->getUri()->getPath())
        ) {
            foreach (Admin::getPlugins() as $plugin) {
                if ($plugin instanceof EventListenerInterface) {
                    EventManager::instance()->on($plugin);
                }
            }
        }

        return $handler->handle($request);
    }
}
