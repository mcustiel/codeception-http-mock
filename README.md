# codeception-http-mock
This Codeception Extension allows developers and testers to use HttpMock to mock external services when running codeception tests.

codeception-http-mock runs an instance of http-mock before your tests run so they can mock external services.
After the tests are finished it will close the connection and turn http-mock off.

## See also

* [http-mock library](https://github.com/InterNations/http-mock)

## Installation

### Composer:

This project is published in packagist, so you just need to add it as a dependency in your composer.json:

```javascript
    "require": {
        // ...
        "mcustiel/codeception-http-mock": "*"
    }
```

If you want to access directly to this repo, adding this to your composer.json should be enough:

```javascript  
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mcustiel/codeception-http-mock"
        }
    ],
    "require": {
        "mcustiel/codeception-http-mock": "dev-master"
    }
}
```

Or just download the release and include it in your path.

## Configuration Example

### Extension

```yaml
# codeception.yml
extensions:
    enabled:
        - Codeception\Extension\HttpMock
    config:
        Codeception\Extension\HttpMock:
            port: 18080 # defaults to http-mock default port
            host: name.for.my.server # defaults to http-mock default host
```

### Module

```yaml
# acceptance.yml
modules:
    enabled:
        - HttpMock
```

## How to use

### Prepare your application

First of all, configure your application so when it is being tested it will replace its external services with http-mock.
For instance, if you make some requests to a REST service located under http://your.rest.interface, replace that url in configuration with the host yoy set up in http-mock extension configuration.

### Write your tests

```php
// YourCest.php
class YourCest extends \Codeception\TestCase\Test
{
    // tests
    public function tryToTest(\AcceptanceTester $I)
    {
        $I->expectRequest()->when()
                ->methodIs('GET')
                ->pathIs('/foo')
            ->then()
                ->body('mocked body')
            ->end();
        $I->doNotExpectAnyOtherRequest();
        $response = file_get_contents('http://localhost:28080/foo');
        $I->assertEquals('mocked body', $response);
    }
}
```
