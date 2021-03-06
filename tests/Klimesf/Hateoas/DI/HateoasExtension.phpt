<?php

use Hateoas\Hateoas;
use Klimesf\Hateoas\DI\HateoasExtension;
use Klimesf\Hateoas\NetteUrlGenerator;
use Nette\DI;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';




class MyExpressionLanguage extends Symfony\Component\ExpressionLanguage\ExpressionLanguage {}

class MyExpressionFunction implements \Hateoas\Expression\ExpressionFunctionInterface
{
	public function getName() { return "my-expression-function"; }

	public function getCompiler() { return null; }

	public function getEvaluator() { return null; }

	public function getContextVariables() { return []; }
}

class MyUrlGenerator implements \Hateoas\UrlGenerator\UrlGeneratorInterface
{
	public function generate($name, array $parameters, $absolute = false) { throw new \LogicException("Method generate() not implemented."); }
}

class MyOtherUrlGenerator implements \Hateoas\UrlGenerator\UrlGeneratorInterface
{
	public function generate($name, array $parameters, $absolute = false) { throw new \LogicException("Method generate() not implemented."); }
}

class MyRelationProviderResolver implements \Hateoas\Configuration\Provider\Resolver\RelationProviderResolverInterface
{
	public function getRelationProvider(\Hateoas\Configuration\RelationProvider $configuration, $object) { throw new \LogicException("Method getRelationProvider() not implemented."); }
}

class MyJsonSerializer implements \Hateoas\Serializer\JsonSerializerInterface {
	public function serializeLinks(array $links, \JMS\Serializer\JsonSerializationVisitor $visitor, \JMS\Serializer\SerializationContext $context) { throw new \LogicException("Method serializeLinks() not implemented."); }
	public function serializeEmbeddeds(array $embeddeds, \JMS\Serializer\JsonSerializationVisitor $visitor, \JMS\Serializer\SerializationContext $context) { throw new \LogicException("Method serializeEmbeddeds() not implemented."); }
}

class MyXmlSerializer implements \Hateoas\Serializer\XmlSerializerInterface {
	public function serializeLinks(array $links, \JMS\Serializer\XmlSerializationVisitor $visitor, \JMS\Serializer\SerializationContext $context) { throw new \LogicException("Method serializeLinks() not implemented."); }
	public function serializeEmbeddeds(array $embeddeds, \JMS\Serializer\XmlSerializationVisitor $visitor, \JMS\Serializer\SerializationContext $context) { throw new \LogicException("Method serializeEmbeddeds() not implemented."); }
}

class MyRouter implements \Nette\Application\IRouter {
	function match(Nette\Http\IRequest $httpRequest) { throw new \LogicException("Method match() not implemented."); }
	function constructUrl(\Nette\Application\Request $appRequest, Nette\Http\Url $refUrl) { throw new \LogicException("Method constructUrl() not implemented."); }
}




// No settings
$compiler = new DI\Compiler;
$compiler->addExtension('hateoas', new HateoasExtension());
$compiler->addConfig([
	'parameters' => [
		'tempDir'   => TEMP_DIR,
		'debugMode' => true,
	],
	'services'   => [
		'router'        => MyRouter::class,
		'linkGenerator' => \Nette\Application\LinkGenerator::class,
		'url'           => \Nette\Http\Url::class,
	]
]);
eval($compiler->compile([], 'Container1'));
$container = new Container1();
Assert::type(Hateoas::class, $container->getService('hateoas.hateoas'));
Assert::type(NetteUrlGenerator::class, $container->getService('hateoas.netteUrlGenerator'));

// Extra settings
$compiler = new DI\Compiler;
$compiler->addExtension('hateoas', new HateoasExtension());
$compiler->addConfig([
	'parameters' => [
		'tempDir'   => TEMP_DIR,
		'debugMode' => true,
	],
	'services'   => [
		'router'              => MyRouter::class,
		'linkGenerator'       => \Nette\Application\LinkGenerator::class,
		'url'                 => \Nette\Http\Url::class,
		'myUrlGenerator'      => MyUrlGenerator::class,
		'myOtherUrlGenerator' => MyOtherUrlGenerator::class,
	],
	'hateoas'    => [
		'cacheDir'                   => TEMP_DIR . '/cache/hateoas',
		'debugMode'                  => false,
		'jsonSerializer'             => MyJsonSerializer::class,
		'xmlSerializer'              => MyXmlSerializer::class,
		'urlGenerators'              => [
			MyUrlGenerator::class,
			"other" => MyOtherUrlGenerator::class,
			"nette" => NetteUrlGenerator::class,
		],
		'expressionContextVariables' => [
			'foo' => 'value',
			'bar' => 'value'
		],
		'expressionLanguage'         => MyExpressionLanguage::class,
		'expressionFunctions'        => [
			MyExpressionFunction::class
		],
		'relationProviderResolvers'  => [
			MyRelationProviderResolver::class
		],
		'includeInterfaceMetadata'   => false,
	]
]);

eval($compiler->compile([], 'Container2'));
$container = new Container2();

Assert::type(Hateoas::class, $container->getService('hateoas.hateoas'));
Assert::type(NetteUrlGenerator::class, $container->getService('hateoas.netteUrlGenerator'));
