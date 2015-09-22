<?php

use Nette\DI;
use Nette\Http;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

if (PHP_SAPI === 'cli') {
	Tester\Environment::skip('Requires CGI SAPI to work with HTTP headers.');
}

$json = \Nette\Utils\Json::encode([
	'test' => true
]);
$halJsonResponse = new \Klimesf\Hateoas\HalJsonResponse($json);

test(function () use ($json, $halJsonResponse) {
	ob_start();
	$halJsonResponse->send(new Http\Request(new Http\UrlScript()), $httpResponse = new Http\Response());
	Assert::same('application/hal+json', $httpResponse->getHeader('Content-type'));
	Assert::same($json, ob_get_clean());
});
