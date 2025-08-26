<?php
namespace models;
class login extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'login';
    protected $fieldConf=[
        'user'=>[
            'belongs-to-one'=>'models\user'
        ],
        'last_login'=>[
            'type'=>'TIMESTAMP',
            'default'=>\DB\SQL\Schema::DF_CURRENT_TIMESTAMP
        ],
        'state'=>[
            'type'=>'BOOLEAN',
            'default'=>0
        ]
    ];
}