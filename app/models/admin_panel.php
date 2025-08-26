<?php
namespace models;
class admin_panel extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'admin_panel';
    protected $fieldConf=[
        'call_request'=>[
            'belongs-to-one'=>'models\call_request',
            'required'=>true
        ],
        'rating'=>[
            'type'=>'INT',
            'required'=>true,
            'default'=>0
        ]
    ];
}