<?php
declare(strict_types=1);

namespace Admin\Authorization\Middleware\UnauthorizedHandler;

use Authorization\Exception\Exception;
use Authorization\Middleware\UnauthorizedHandler\RedirectHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageRedirectHandler extends RedirectHandler
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
        $url = $this->getUrl($request, $options);

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
    {{MESSAGE}}<br />
    <a href="{{URL}}">{{URL}}</a>
</p>

<noscript>
<p class="noscript">You need JavaScript enabled for automatic redirect to work. Please click the link above.</p>
</noscript>

<script>
var redirectTimeout = {{TIMEOUT}};
var redirectUrl = "{{URL}}";
console.log("[unauthorized] Redirecting to " + redirectUrl + " in " + redirectTimeout/1000 + " seconds");
setTimeout(function() {
    console.log("[unauthorized] Redirecting to " + redirectUrl);
    window.location.href=redirectUrl
}, redirectTimeout)
</script>
</body>
</html>
HTML;
        //@todo Make params configurable
        $replace = [
            '/\{\{TIMEOUT\}\}/' => 2000,
            '/\{\{TITLE\}\}/' => __('UNAUTHORIZED'),
            '/\{\{MESSAGE\}\}/' => __(
                'You are not allowed to access the resource at {0}',
                $request->getUri()->getPath()
            ),
            '/\{\{URL\}\}/' => $url,
        ];

        $html = preg_replace(
            array_keys($replace),
            array_values($replace),
            $html
        );

        return $response
            ->withoutHeader('Location')
            ->withType('html')
            ->withStringBody($html);
    }
}
