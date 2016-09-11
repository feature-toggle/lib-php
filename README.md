# Feature Toggle

A PHP client library for interacting with [featuretoggle.com](https://featuretoggle.com).  This library is under active development and is likely to change frequently.  Bug reports and pull requests are welcome.

## Installation

Install with [Composer](https://getcomposer.org/)

```bash
php composer.phar require featuretoggle/featuretoggle-lib-php
```

## Usage

```php
require 'vendor/autoload.php';

// Create a new FTClient with your customer and environment API key's
$featuretoggle = new FeatureToggle\FTClient('customerKey', 'environmentKey', array('options'));

// Get features
$features = $featuretoggle->getFeatures();

// Check feature status
$enabled = $featuretoggle->isEnabled('feature_key');
if($enabled) {
    // Feature is enabled, do something
}
else {
    // Feature is disabled, do something else
}
```

### Configuration Options
The library caches responses from received the Feature Toggle API locally to limit the number of requests. The default cache timeout is 300 seconds (5 minutes).  You can adjust the cache timeout by providing the 'cache_timeout' config option when initializing the library.

```php
array(
    'cache_timeout' => SECONDS, // optional, defaults to 300 seconds
)
```
## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/featuretoggle/featuretoggle-lib-php.


## License

The library is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).
