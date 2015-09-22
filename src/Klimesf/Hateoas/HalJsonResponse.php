<?php


namespace Klimesf\Hateoas;

use Klimesf\Hateoas;
use Nette;

/**
 * HAL JSON Nette Application Response.
 * @package   Klimesf\Hateoas
 * @author    Filip Klimes <filip@filipklimes.cz>
 */
class HalJsonResponse implements Nette\Application\IResponse
{

	/** @var string */
	private $json;

	/**
	 * @param  string $json
	 */
	public function __construct($json)
	{
		$this->json = $json;
	}

	/**
	 * Sends response to output.
	 * @param Nette\Http\IRequest  $httpRequest
	 * @param Nette\Http\IResponse $httpResponse
	 */
	public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
	{
		$httpResponse->setContentType('application/hal+json');
		$httpResponse->setExpiration(false);
		echo $this->json;
	}

}
