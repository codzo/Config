# Config
Configuration handler.

This package will read configuration files from a directory and allow retrieval of data in several ways.

## Installation
```composer
composer require codzo/config
```

## Usage

```php
$config = new \Codzo\Config\Config('./config');

// retrieve the config
$app = $config->get('app');
$enabled = $app['ini']['enabled'];

// or quickly
$enabled = $config->get('app.ini.enabled');

```

## The configuration directory
This package requires a directory as home to all configuration files. 
Files in this directory will be considered as configuration files if filetype supported.

This package will not load any file in sub-directory.

## The configuration files
These file types are supported:
+ php
+ ini
+ xml

PHP configuration files shall return an array.

INI files will be parsed using `parse_ini_file()` method.

XML files will be parsed using `simplexml_load_file()` method.

The configuration loaded from all files will then be merged together. Duplicated items will be overwritten without any warning.

## Retrieve the configuration
Configurations can be retrieved in several ways.
+ as an array: Use keys to navigate in multiplication arrays
+ quick search: Concat the keys into a path and get the configuration quickly.

