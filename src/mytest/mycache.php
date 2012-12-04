<?php
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
