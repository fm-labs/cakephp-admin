<?php
declare(strict_types=1);

namespace Admin\Authorization\Middleware\UnauthorizedHandler;

use Authorization\Exception\Exception;
use Authorization\Middleware\UnauthorizedHandler\RedirectHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FlashRedirectHandler extends RedirectHandler
{
    /**
     * Handles the unauthorized request. The modified response should be returned.
     *
     * @param \Authorization\Exception\Exception $exception Authorization exception thrown by the application.
     * @param \Psr\Http\Message\ServerRequestInterface $request Server request.
     * @param array $options Options array.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(
        Exception $exception,
        ServerRequestInterface $request,
        array $options = []
    ): ResponseInterface {
        $options += $this->defaultOptions;
        $flash = [
            'key' => 'flash',
            'element' => 'flash/error',
            'params' => [],
        ];

        $response = parent::handle($exception, $request, $options);

        if ($request instanceof \Cake\Http\ServerRequest) {
            $session = $request->getSession();
            $messages = $session->read('Flash.' . $flash['key']);
            $messages[] = [
                'message' => __(
                    'UNAUTHORIZED! You are not allowed to access the resource at {0}',
                    $request->getUri()->getPath()
                ),
                'key' => $flash['key'],
                'element' => $flash['element'],
                'params' => $flash['params'],
            ];

            $session->write('Flash.' . $flash['key'], $messages);
        }

        return $response;
    }
}
