<?php


namespace Klimesf\Hateoas;

use Hateoas\UrlGenerator\UrlGeneratorInterface;
use Nette\Application\LinkGenerator;

/**
 * Generates HAL URLs using the Ntte LinkGenerator.
 * @package   Klimesf\Hateoas
 * @author    Filip Klimes <filip@filipklimes.cz>
 */
class NetteUrlGenerator implements UrlGeneratorInterface
{

	/**
	 * @var LinkGenerator
	 */
	private $linkGenerator;

	/**
	 * UrlGenerator constructor.
	 * @param LinkGenerator $linkGenerator
	 */
	public function __construct(LinkGenerator $linkGenerator)
	{
		$this->linkGenerator = $linkGenerator;
	}

	/**
	 * Generates URL from the given parameters.
	 * @param string  $name
	 * @param array   $parameters
	 * @param boolean $absolute
	 * @return string
	 */
	public function generate($name, array $parameters, $absolute = false)
	{
		if ($absolute) {
			$name = '//' . $name;
		}
		return $this->linkGenerator->link($name, $parameters);
	}

}
