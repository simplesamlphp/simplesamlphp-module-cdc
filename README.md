# Common Domain Cookies

![Build Status](https://github.com/simplesamlphp/simplesamlphp-module-cdc/actions/workflows/php.yml/badge.svg)
[![Coverage Status](https://codecov.io/gh/simplesamlphp/simplesamlphp-module-cdc/branch/master/graph/badge.svg)](https://codecov.io/gh/simplesamlphp/simplesamlphp-module-cdc)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/simplesamlphp/simplesamlphp-module-cdc/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/simplesamlphp/simplesamlphp-module-cdc/?branch=master)
[![Type Coverage](https://shepherd.dev/github/simplesamlphp/simplesamlphp-module-cdc/coverage.svg)](https://shepherd.dev/github/simplesamlphp/simplesamlphp-module-cdc)
[![Psalm Level](https://shepherd.dev/github/simplesamlphp/simplesamlphp-module-cdc/level.svg)](https://shepherd.dev/github/simplesamlphp/simplesamlphp-module-cdc)

## Install

Install with composer

```bash
vendor/bin/composer require simplesamlphp/simplesamlphp-module-cdc
```

## Configuration

Next thing you need to do is to enable the module: in `config.php`,
search for the `module.enable` key and set `cdc` to true:

```php
'module.enable' => [
    'cdc' => true,
    …
],
```
