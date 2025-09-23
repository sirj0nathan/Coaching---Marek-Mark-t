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

    public function getDelete(\Base $base, $params)
    {
        if (!$base->get('SESSION.uid') || $base->get('SESSION.role') !== 'admin') {
            \Flash::instance()->addMessage('Nemáte oprávnění', 'danger');
            $base->reroute('/');
            return;
        }

        $service = new \models\services();
        $service->load(['id=?', $params['id']]);

        if (!$service->dry()) {
            $service->erase();
            \Flash::instance()->addMessage('Služba byla smazána', 'success');
        }

        $base->reroute('/admin/services');
    }

    public function postEdit(\Base $base, $params)
    {
        if (!$base->get('SESSION.uid') || $base->get('SESSION.role') !== 'admin') {
            \Flash::instance()->addMessage('Nemáte oprávnění', 'danger');
            $base->reroute('/');
            return;
        }

        $service = new \models\services();
        $service->load(['id=?', $params['id']]);

        if (!$service->dry()) {
            $service->name = $base->get('POST.name');
            $service->description = $base->get('POST.description');
            $service->price = $base->get('POST.price');
            $service->save();

            \Flash::instance()->addMessage('Služba byla aktualizována', 'success');
        }
        $base->reroute('/admin/services');
    }

    public function getEditUser(\Base $base, $params) {
        // Load user by ID
        $user = new \models\user();
        $user->load(['id=?', $params['id']]);

        if ($user->dry()) {
            \Flash::instance()->addMessage('Uživatel nenalezen', 'danger');
            $base->reroute('/admin/users');
            return;
        }

        // Manually load diet information for this user
        $diet = new \models\diet();
        $diet->load(['user=?', $user->id]);  // Using 'user' instead of 'user_id'

        // If no diet record exists, create one
        if ($diet->dry()) {
            $diet->user = $user->id;  // Using 'user' instead of 'user_id'
            $diet->alergies = '';
            $diet->dietary_preferences = '';
            $diet->dietary_restrictions = '';
            $diet->diet_href = '';
            $diet->save();
        }

        // Set template variables
        $base->set('user', $user);
        $base->set('diet', $diet);
        $base->set('title', 'Úprava uživatele');
        $base->set('content', 'admin/edit_user.html');
        echo \Template::instance()->render('index.html');
    }




    public function postEditUser(\Base $base, $params)
    {
        if (!$base->get('SESSION.uid') || $base->get('SESSION.role') !== 'admin') {
            \Flash::instance()->addMessage('Nemáte oprávnění', 'danger');
            $base->reroute('/');
            return;
        }

        $user = new \models\user();
        $user->load(['id=?', $params['id']]);

        if ($user->dry()) {
            \Flash::instance()->addMessage('Uživatel nenalezen', 'danger');
            $base->reroute('/admin/users');
            return;
        }

        $user->name = $base->get('POST.name');
        $user->email = $base->get('POST.email');
        if ($base->get('POST.password')) {
            $user->password = password_hash($base->get('POST.password'), PASSWORD_BCRYPT);
        }
        $user->role = $base->get('POST.role');
        $user->save();

        \Flash::instance()->addMessage('Uživatel byl aktualizován', 'success');
        $base->reroute('/admin/users');
    }
}