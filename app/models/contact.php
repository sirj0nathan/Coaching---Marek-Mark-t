<?php
namespace models;
class contact extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'contact';
    protected $fieldConf=[
        'name' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'email' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'phone' => [
            'type' => 'VARCHAR128',
            'required' => false,
            'nullable' => true,
        ],
        'subject' => [
            'type' => 'VARCHAR128',
            'required' => false,
            'nullable' => true,
        ],
        'message' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
            ],
        'time' => [
            'type' => 'TIMESTAMP',
            'required' => true,
            ]
    ];
}