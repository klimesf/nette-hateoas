# Hateoas

[![Build Status](https://travis-ci.org/klimesf/nette-hateoas.svg?branch=master)](https://travis-ci.org/klimesf/nette-hateoas)
[![Latest Stable Version](https://poser.pugx.org/klimesf/nette-hateoas/version)](https://packagist.org/packages/klimesf/nette-hateoas)

Hateoas integration into Nette Framework.

For documentation, please refer to [Hateoas GitHub](https://github.com/willdurand/Hateoas)
or to [Official Hateoas webpage](http://hateoas-php.org).

Requirements
============

Hateoas requires

- PHP 5.4 or higher
- [Nette Framework](https://github.com/nette/nette)
- [Hateoas](https://github.com/willdurand/Hateoas)

Installation
============

Install Hateoas via Composer.

```sh
composer require klimesf/nette-hateoas
```

Configuration
=============

```yml
extensions:
	hateoas: Klimesf\Hateoas\DI\HateoasExtension
	
hateoas:
	cacheDir: %tempDir%/cache/hateoas
	debugMode: %debugMode%
	jsonSerializer: App\My\JsonSerializer
	xmlSerializer: App\My\XmlSerializer
	urlGenerators:
		- App\My\DefaultUrlGenerator
		other: App\My\OtherUrlGenerator
	expressionContextVariables:
		foo: "value"
		bar: "value"
	expressionLanguage: App\My\ExpressionLanguage
	expressionFunctions:
		- App\My\ExpressionFunction
		- App\My\OtherExpressionFunction
	relationProviderResolvers:
		- App\My\RelationProviderResolver
		- App\My\OtherRelationProviderResolver
```

Usage
=====

Require `Hateoas\Hateoas` class and send the json.

```php
class MyPresenter extends Nette\Application\UI\Presenter
{

	/** @var Hateoas\Hateoas @inject */
	public $hateoas;
	
	public function actionDefault()
	{
		$entity = // ...
		$json = $this->hateoas->serialize($entity, 'json');
		$this->sendResponse(new Klimesf\Hateoas\HalJsonResponse($json)); // Sends HAL JSON response
	}

}
```
