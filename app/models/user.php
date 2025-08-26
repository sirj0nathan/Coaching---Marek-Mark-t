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
        'password' => [
            'type' => 'TEXT',
            'required' => true,
        ],
        'role' => [
            'belongs-to-one' => 'models\role',
            'default' => 3
        ],
        'dochazka' => [
            'has-many' => 'models\dochazka',
        ],
        'login' => [
            'has-many' => 'models\login'
        ],
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