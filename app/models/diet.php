<?php
namespace models;
class diet extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'diet';
    protected $fieldConf=[
        'user'=>[
            'belongs-to-one'=>'models\user'
        ],
        'alergies' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'dietary_preferences' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'dietary_restrictions' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'diet_href' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ]
    ];
}