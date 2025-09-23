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
        // First, tear down all tables in the correct order (dependent tables first)
        \models\review::setdown();
        \models\diary::setdown();
        \models\diet::setdown();
        \models\login::setdown();
        \models\user::setdown();
        \models\role::setdown();
        \models\services::setdown();
        \models\contact::setdown();

        // Then set up all tables in proper order
        \models\role::setup();
        \models\user::setup();
        \models\login::setup();
        \models\diet::setup();
        \models\diary::setup();
        \models\review::setup();
        \models\services::setup();
        \models\contact::setup();

        // Initialize roles
        $this->nastavRole([
            ['name' => 'admin', 'description' => 'Administrátor'],
            ['name' => 'user', 'description' => 'Uživatel'],
            ['name' => 'guest', 'description' => 'Host']
        ]);

        \Base::instance()->reroute('/');
    }
}