<?php

namespace models;

class user extends \DB\Cortex
{
    protected $db = 'DB',
        $table = 'User';
    protected $fieldConf = [
        'name' => [
            'type' => 'VARCHAR128',
            'required' => true,
        ],
        'surname' => [
            'type' => 'VARCHAR128',
            'required' => true,
        ],
        'email' => [
            'type' => 'VARCHAR128',
            'required' => true,
            'unique' => true,
        ],
        'date_of_birth' => [
            'type' => 'DATE',
            'required' => true,
        ],
        'password' => [
            'type' => 'TEXT',
            'required' => true,
        ],
        'phone_number' => [
            'type' => 'VARCHAR32',
            'required' => false,
            'nullable' => true,
        ],
        'weight' => [
            'type' => 'DOUBLE',
            'required' => false,
            'nullable' => true,
        ],
        'medications' => [
            'type' => 'VARCHAR256',
            'required' => false,
            'nullable' => true,
        ],
        'approved' => [
            'type' => 'INT',
            'required' => true,
            'default' => 0
        ],
        'diet' => [
            'has-one' => 'models\diet',
        ],
        'role' => [
            'belongs-to-one' => 'models\role',
            'default' => 3
        ],
        'login' => [
            'has-many' => 'models\login'
        ],
        'review' => [
            'has-one' => 'models\review'
        ]
    ];
    public function set_password($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }
    public function get_lastlogin()
    {
        $login = new login();
        $lastLogin = $login->findone(['user=?', $this->id], ['order' => 'id DESC']);
        return $lastLogin ? $lastLogin->dry() ? null : $lastLogin->last_login : null;
    }
}