<?php

namespace controllers;
class install
{

    private function nastavRole($pole)
    {
        $roleModel = new \models\role();
        foreach ($pole as $r) {
            $existing = $roleModel->findone("name = '{$r['name']}'");
            if (!$existing) {
                $role = new \models\role();
                $role->name = $r['name'];
                $role->description = $r['description'];
                $role->save();
                unset($role);
            }
        }
    }



    public function setup()
    {
        //\models\review::setdown();
        //\models\diary::setdown();
        //\models\diet::setdown();
        //\models\login::setdown();
        //\models\user::setdown();
        //\models\role::setdown();
        //\models\services::setdown();
        //\models\contact::setup();

        \models\role::setup();
        \models\user::setup();
        \models\login::setup();
        \models\diet::setup();
        \models\diary::setup();
        \models\review::setup();
        \models\services::setup();
        \models\contact::setup();


        $this->nastavRole([
            ['name'=>'admin','description'=>'Administrátor'],
            ['name'=>'user','description'=>'Uživatel'],
            ['name'=>'guest','description'=>'Host']
        ]);

        \Base::instance()->reroute('/');
    }
}