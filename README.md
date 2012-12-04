demo-cacheable-behavior-bundle
==============================

This is a demo for [cacheable-behavior-bundle](https://github.com/RickySu/cacheable-behavior-bundle).

Installation
------------

### Install Components With Composer :

```
php composer.phar install
```

### Edit config for build time.

```ini
# conf/build.properties
# Database driver
propel.database = sqlite
propel.database.url = sqlite:/tmp/foo.db


# Project name
propel.project = orm

propel.behavior.cacheable.class = RickySu\CacheableBehaviorBundle\Behavior\CacheableBehavior

propel.output.dir = PATH_TO_MY_TEST
propel.php.dir = ${propel.output.dir}/src
propel.phpconf.dir = ${propel.output.dir}/src
propel.sql.dir = ${propel.output.dir}/conf/db/sql

```

### Edit config for runtime.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <propel>
        <datasources default="bookstore">
            <datasource id="bookstore">
                <adapter>sqlite</adapter> <!-- sqlite, mysql, mssql, oracle, or pgsql -->
                <connection>
                    <dsn>sqlite:/tmp/foo.db</dsn>
                    <user></user>
                    <password></password>
                </connection>
            </datasource>
        </datasources>
    </propel>
</config>
```

### Edit tagcache config for your need

```php
<?php
//src/mytest/mycache
namespace mytest;

use RickySu\CacheableBehaviorBundle\CacheableBehaviorFactory;

class mycache extends CacheableBehaviorFactory
{

    public function getConfig()
    {
        return array(
            'driver'   => 'Memcache',
            'namespace' => 'test_name_space',
            'options' => array(
                'hashkey' => false,
                'enable_largeobject' => false,
                'servers' => array(
                    'localhost:11211:10',
                ),
            ),
        );
    }

}
```

### Build all.

```
cd conf
../vendor/bin/propel-gen
```

### Init sqlite db

```
../vendor/bin/propel-gen insert-sql
```

For more information please read the [Propel document](http://propelorm.org/documentation/).

