[![Build Status](https://img.shields.io/travis/zircote/swagger-php/master.svg?style=flat-square)](https://travis-ci.org/zircote/swagger-php)
[![Total Downloads](https://img.shields.io/packagist/dt/zircote/swagger-php.svg?style=flat-square)](https://packagist.org/packages/zircote/swagger-php)
[![License](https://img.shields.io/badge/license-Apache-blue.svg?style=flat-square)](LICENSE-2.0.txt)

# apidoc

A sample way to generate [Swagger](http://swagger.io) documentation for your RESTful API using [doctrine annotations](http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html).

## Features

 - Compatible with the Swagger 2.0 specification
 - Support multi-apps

## Installation (with [Composer](https://getcomposer.org))
1. Clone the code
2. Install vendors
```sh
composer install
```
3. Configure the host like this
```
vi /etc/hosts
# add IP VHOST
127.0.0.1 apidoc.local
```
4. Configure the virtual host for Apache
```
<VirtualHost *:80>
 ServerName apidoc.local
    DocumentRoot "/www/apidoc/public"
	
    <Directory "/www/apidoc/public">
        # use mod_rewrite for pretty URL support
        RewriteEngine on
        # If a directory or a file exists, use the request directly
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        # Otherwise forward the request to index.php
        RewriteRule . index.php

        # use index.php as index file
        DirectoryIndex index.html index.php
    </Directory>
</VirtualHost>
```

## Usage
Add annotations to your php files.
```php
/**
 * @SWG\Info(title="My First API", version="0.1")
 */

/**
 * @SWG\Get(
 *     path="/api/resource.json",
 *     @SWG\Response(response="200", description="An example resource")
 * )
 */
```
See the Examples directory for more.

## Configure
1. Open config/app.php
2. To Config the "app-list"
3. browse http://YOUR_HOST/APP_NAME, for example: 
http://apidoc.local/apiv1
http://apidoc.local/apiv2

## More on Swagger

  * http://swagger.io/
  * https://github.com/swagger-api/swagger-spec/
  * http://bfanger.github.io/swagger-explained/

## Contributing

Feel free to submit [Github Issues](https://github.com/davin.bao/apidoc/issues)
or pull requests.
