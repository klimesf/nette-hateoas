<?php


namespace Klimesf\Hateoas\DI;

use Hateoas\Hateoas;
use Nette\DI\CompilerExtension;

/**
 * Hateoas Nette Compiler Extension.
 * @package   Klimesf\Hateoas\DI
 * @author    Filip Klimes <filip@filipklimes.cz>
 */
class HateoasExtension extends CompilerExtension
{

	protected $defaults = [
		'cacheDir'                   => '%tempDir%/cache/hateoas',
		'debugMode'                  => '%debugMode',
		'jsonSerializer'             => 'default',
		'xmlSerializer'              => 'default',
		'urlGenerators'              => [],
		'expressionContextVariables' => [],
		'expressionLanguage'         => null,
		'expressionFunctions'        => [],
		'relationProviderResolvers'  => null,
		'includeInterfaceMetadata'   => true,
	];

	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->getConfig($this->defaults));
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->prefix('hateoasBuilder'))
			->setClass(HateoasBuilder::class, [$config])
			->setInject(false)
			->setAutowired(false);

		$container->addDefinition($this->prefix('hateoas'))
			->setClass(Hateoas::class)
			->setFactory('@' . $this->prefix('hateoasBuilder') . '::getHateoas')
			->setInject(false);
	}

}
