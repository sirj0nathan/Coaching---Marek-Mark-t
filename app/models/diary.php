<?php
namespace models;
class diary extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'diary';
    protected $fieldConf=[
        'user'=>[
            'belongs-to-one'=>'models\user'
        ],
        'title' => [
            'type' => 'VARCHAR256',
            'required' => true,
        ],
        'content' => [
            'type' => 'TEXT',
            'required' => true,
        ],
        'mood' => [
            'type' => 'VARCHAR128',
            'required' => false,
            'nullable' => true,
        ],
        'weight' => [
            'type' => 'DOUBLE',
            'required' => false,
            'nullable' => true,
        ],
        'exercise' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'created' => [
            'type' => 'TIMESTAMP',
            'default' => \DB\SQL\Schema::DF_CURRENT_TIMESTAMP,
        ]
    ];
}