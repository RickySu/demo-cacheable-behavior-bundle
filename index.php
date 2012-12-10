<?php

use mytest\mycache;

$loader=include __DIR__.'/vendor/autoload.php';
$loader->add('',__DIR__.'/src');

mycache::init();
Propel::init(__DIR__.'/src/orm-conf.php');

$Author=new orm\Author();
$Author->setFirstName('Ricky');
$Author->setLastName('Su');
$Author->save();

orm\AuthorQuery::create()->findPk($Author->getId());
