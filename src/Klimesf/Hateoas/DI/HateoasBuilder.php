<?php


namespace Klimesf\Hateoas\DI;

use Hateoas\HateoasBuilder as Builder;
use Klimesf\Hateoas\NetteUrlGenerator;
use Nette\DI\Container;

/**
 * Builds Hateoas service with given configuration.
 * @package   Klimesf\Hateoas\DI
 * @author    Filip Klimes <filip@filipklimes.cz>
 */
class HateoasBuilder
{

	/**
	 * @var array
	 */
	private $config;

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * HateoasBuilder constructor.
	 * @param array             $config
	 * @param Container         $container
	 */
	public function __construct(array $config, Container $container)
	{
		$this->config = $config;
		$this->container = $container;
	}

	/**
	 * @return \Hateoas\Hateoas
	 */
	public function getHateoas()
	{
		$hateoasBuilder = Builder::create()
			->setCacheDir($this->config['cacheDir'])
			->setDebug($this->config['debugMode']);

		$this->setJsonSerializer($hateoasBuilder);
		$this->setXmlSerializer($hateoasBuilder);
		$this->addUrlGenerators($hateoasBuilder);

		if (!empty($this->config['expressionContextVariables'])) {
			$this->addExpressionContextVariables($hateoasBuilder);
		}

		if ($this->config['expressionLanguage']) {
			$hateoasBuilder->setExpressionLanguage(new $this->config['expressionLanguage']);
		}

		if (!empty($this->config['expressionFunctions'])) {
			$this->setExpressionLanguageFunctions($hateoasBuilder);
		}

		if ($this->config['relationProviderResolvers']) {
			$this->addRelationProviderResolvers($hateoasBuilder);
		}

		$hateoasBuilder->includeInterfaceMetadata($this->config['includeInterfaceMetadata']);

		return $hateoasBuilder->build();
	}

	/**
	 * @param Builder $hateoasBuilder
	 */
	private function addUrlGenerators(Builder & $hateoasBuilder)
	{
		if (!empty($this->config['urlGenerators'])) {
			$hasDefault = false;
			foreach ($this->config['urlGenerators'] as $name => $generator) {
				if (is_numeric($name) && !$hasDefault) {
					$name = null;
					$hasDefault = true;
				}
				$hateoasBuilder->setUrlGenerator($name, $this->container->getByType($generator));
			}
		}
	}

	/**
	 * @param $hateoasBuilder
	 */
	public function setXmlSerializer(Builder & $hateoasBuilder)
	{
		if ($this->config['xmlSerializer'] === 'default') {
			$hateoasBuilder->setDefaultXmlSerializer();
		} else {
			$hateoasBuilder->setXmlSerializer(new $this->config['xmlSerializer']);
		}
	}

	/**
	 * @param $hateoasBuilder
	 */
	public function setJsonSerializer(Builder & $hateoasBuilder)
	{
		if ($this->config['jsonSerializer'] === 'default') {
			$hateoasBuilder->setDefaultJsonSerializer();
		} else {
			$hateoasBuilder->setJsonSerializer(new $this->config['jsonSerializer']);
		}
	}

	/**
	 * @param Builder $hateoasBuilder
	 */
	private function addExpressionContextVariables(Builder & $hateoasBuilder)
	{
		foreach ($this->config['expressionContextVariables'] as $name => $value) {
			$hateoasBuilder->setExpressionContextVariable($name, $value);
		}
	}

	/**
	 * @param Builder $hateoasBuilder
	 */
	private function setExpressionLanguageFunctions(Builder & $hateoasBuilder)
	{
		foreach ($this->config['expressionFunctions'] as $function) {
			$hateoasBuilder->registerExpressionFunction(new $function);
		}
	}

	/**
	 * @param Builder $hateoasBuilder
	 */
	private function addRelationProviderResolvers(Builder & $hateoasBuilder)
	{
		foreach ($this->config['relationProviderResolvers'] as $resolver) {
			$hateoasBuilder->addRelationProviderResolver(new $resolver);
		}
	}

}
