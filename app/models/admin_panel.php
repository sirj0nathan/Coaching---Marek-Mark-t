<?php
namespace models;
class admin_panel extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'admin_panel';
    protected $fieldConf=[
        'call_request'=>[
            'type'=>'VARCHAR128',
            'required'=>true
        ],
        'review_text'=>[
            'type'=>'VARCHAR256',
            'default'=>0
        ],
        'rating'=>[
            'type'=>'INT',
            'required'=>true,
            'default'=>0
        ]
    ];
}