<?php

namespace controllers;

class admin
{
    public function getIndex(\Base $base)
    {
        $base->set('title', 'Admin');
        $base->set('content', 'admin/home.html');
        echo \Template::instance()->render('index.html');
    }

    public function getUsers(\Base $base)
    {
        $userModel = new \models\user();
        $users = $userModel->find(['1=1'], ['order' => 'id DESC']);
        $base->set('users', $users);
        $base->set('title', 'Správa uživatelů');
        $base->set('content', 'admin/users.html');
        echo \Template::instance()->render('index.html');
    }

    public function getServices(\Base $base)
    {
        $serviceModel = new \models\services();
        $services = $serviceModel->find(['1=1'], ['order' => 'id DESC']);
        $base->set('services', $services);
        $base->set('title', 'Správa služeb');
        $base->set('content', 'admin/services.html');
        echo \Template::instance()->render('index.html');
    }

    public function postServices(\Base $base)
    {
        $service = new \models\services();
        $service->copyfrom($base->get('POST'));
        $service->save();
        \Flash::instance()->addMessage('Služba byla úspěšně přidána.', 'success');
        $base->reroute('/admin/services');
    }
}