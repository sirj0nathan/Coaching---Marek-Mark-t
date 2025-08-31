<?php
namespace models;
class review extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'review';
    protected $fieldConf=[
        'user'=>[
            'belongs-to-one'=>'models\user'
        ],
        'rating' => [
            'type' => 'INT8',
            'required' => true,
            'default' => 0
        ],
        'title'=>[
            'type'=>'VARCHAR128',
            'required'=>true
        ],
        'review_text'=>[
            'type'=>'VARCHAR256',
            'default'=>0
        ]
    ];
}