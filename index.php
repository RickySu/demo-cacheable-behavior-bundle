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

$Publisher=new orm\Publisher();
$Publisher->setName("Test Publisher");
$Tag1=new orm\Tag();
$Tag1->setName('tag1');
$Tag2=new orm\Tag();
$Tag2->setName('tag2');

$Book=new orm\Book();
$Book->setTitle("this is Title");
$Book->setAuthor($Author);
$Book->setISBN('ISBNTest');
$Book->setPublisher($Publisher);
$Book->addTag($Tag1);
$Book->addTag($Tag2);
$Book->save();

$Author=orm\AuthorQuery::create()->findPk($Author->getId());

foreach($Author->getBooks() as $Book){
    echo "{$Book->getPublisher()->getName()} => {$Book->getTitle()} with";
    foreach($Book->getTags() as $Tag){
        echo " {$Tag->getName()}";
    }
    echo "\n";
}
