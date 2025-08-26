<?php

namespace models;
class role extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'role';
    protected $fieldConf = [
        'name' => [
            'type' => 'VARCHAR128',
            'required' => true,
            'unique' => true,
            'nullable' => false,
            'index' => true
        ],
        'description' => [
            'type' => 'VARCHAR256',
            'required' => true
        ],
        'user' => [
            'has-many' => ['models\user', 'role']
        ]
    ];
}