<?php

namespace controllers;
class install
{

    private function nastavRole($pole)
    {
        foreach ($pole as $r) {
            $role = new \models\role();
            $role->name = $r['name'];
            $role->description = $r['description'];
            $role->save();
            unset($role);
        }
    }


    public function setup()
    {
        \models\user::setdown();
        \models\user::setup();
        \models\login::setdown();
        \models\login::setup();
        \models\role::setdown();
        \models\role::setup();

        $this->nastavRole([
            ['name'=>'admin','description'=>'Administrátor'],
            ['name'=>'user','description'=>'Uživatel'],
            ['name'=>'guest','description'=>'Host']
        ]);

        \Base::instance()->reroute('/');
    }
}