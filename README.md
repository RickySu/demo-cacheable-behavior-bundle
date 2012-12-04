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

### how cache works

```php
<?php
//index.php
use mytest\mycache;

$loader=include __DIR__.'/vendor/autoload.php';
$loader->add('',__DIR__.'/src');

mycache::init();
\Propel::init(__DIR__.'/src/orm-conf.php');

$Author=new orm\Author();
$Author->setFirstName('Ricky');
$Author->setLastName('Su');
$Author->save();

orm\AuthorQuery::create()->findPk($Author->getId());
```

cacheable behavior will build cache code for find pk.

```php
<?php
// src/orm/om/BaseAuthorQuery.php
abstract class BaseAuthorQuery extends ModelCriteria
{
    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed     $key Primary key to use for the query
     * @param PropelPDO $con an optional connection object
     *
     * @return Author|Author[]|mixed the result, formatted by the current formatter
     */
    public function findPk($pk, $con = null)
    {
        $id=$pk;
        $CacheKey="Model:Author-id:"."-'".addslashes($id)."'";
        $Cache=$this->getTagcache();
        if ($Obj=$Cache->get($CacheKey)) {
            return $Obj;
        }
        if ($Obj=$this->rebuild_findPk($pk,$con)) {
            $Cache->set($CacheKey,$Obj);
        }

        return $Obj;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Author|Author[]|mixed the result, formatted by the current formatter
     */
    protected function rebuild_findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AuthorPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AuthorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }
}
```

For more information please read the [Propel document](http://propelorm.org/documentation/).

