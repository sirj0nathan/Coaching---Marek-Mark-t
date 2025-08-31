<?php
namespace models;
class services extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'services';
    protected $fieldConf=[
        'name' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'description' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'price' => [
            'type' => 'FLOAT',
            'required' => false,
            'nullable' => true,
        ]
    ];
}