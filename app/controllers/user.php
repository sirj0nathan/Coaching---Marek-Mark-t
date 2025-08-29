<?php

namespace controllers;

class user
{
    public function postRegister(\Base $base)
    {
        $user = new \models\user();
        $user->copyfrom($base->get('POST'));
        $user->save();
        \Flash::instance()->addMessage('Uzivatel byl zaslan pro overeni', 'success');
        $base->reroute('/');
    }

    public function getRegister(\Base $base)
    {
        $base->set('title', 'Register');
        $base->set('content', '/user/register.html');
        echo \Template::instance()->render('index.html');
    }

    public function getLogin(\Base $base)
    {
        $base->set('title', 'Prihlaseni');
        $base->set('content', '/user/login.html');
        echo \Template::instance()->render('index.html');
    }

    public function postLogin(\Base $base)
    {
        $uzivatel = new \models\User();
        $uz = $uzivatel->findone(['email=?', $base->get('POST.email')]);
        if ($uz === false) {
            \Flash::instance()->addMessage('Uživatel neexistuje', 'danger');
            $base->reroute('/login');
        }
        $login = new \models\login();
        $login->user = $uz;
        if (!password_verify($base->get('POST.password'), $uz->password)) {
            \Flash::instance()->addMessage('Špatné heslo, nebo jmeno', 'danger');
            $login->save();
            $base->reroute('/login');
        }
        if ($uz->approved == 0) {
            \Flash::instance()->addMessage('Uživatel není schválen adminem', 'danger');
            $login->save();
            $base->reroute('/login');
        }
        $login->state = 1;
        $login->save();
        $base->set('SESSION.uid', $uz->id);
        $base->set('SESSION.name', $uz->name, $uz->surname);
        $base->set('SESSION.surname', $uz->surname);
        $base->set('SESSION.role', $uz->role->name);
        $base->set('SESSION.email', $uz->email);
        $base->set('SESSION.phone', $uz->phone_number);
        \Flash::instance()->addMessage('Uživatel prihlášen', 'success');
        $base->reroute('/');
    }

    public function getLogout(\Base $base)
    {
        $base->clear('SESSION');
        \Flash::instance()->addMessage('Byl jsi uspesne odhlasen', 'success');
        $base->reroute('/');
    }

    public function getProfile(\Base $base, $params = [])
    {
        // Check if user is logged in
        $sessionUserId = $base->get('SESSION.uid');
        if (!$sessionUserId) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        // Get user ID from URL parameter or use current session
        $userId = isset($params['id']) ? $params['id'] : $sessionUserId;

        // Check if user is trying to access their own profile only
        if ($userId != $sessionUserId) {
            \Flash::instance()->addMessage('Nemáte oprávnění zobrazit tento profil', 'danger');
            $base->reroute('/');  // Změna z '/user/profile' na '/'
            return;
        }

        // Load user data
        $userModel = new \models\user();
        $userData = $userModel->findone(['id=?', $userId]);

        if (!$userData) {
            \Flash::instance()->addMessage('Uživatel neexistuje', 'danger');
            $base->reroute('/');
            return;
        }

        // Load diet information if exists
        $dietModel = new \models\diet();
        $dietData = $dietModel->findone(['user=?', $userId]);

        // Set template variables
        $base->set('user', $userData);
        if ($dietData) {
            $base->set('diet', $dietData);
        }
        $base->set('title', 'Můj profil');
        $base->set('content', 'user/profile.html');

        echo \Template::instance()->render('index.html');
    }

    public function getEdit(\Base $base)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        $uzivatel = new \models\user();
        $base->set('user', $uzivatel->findone(["id=?", $base->get('SESSION.uid')]));

        // Load diet info
        $diet = new \models\diet();
        $dietData = $diet->findone(['user=?', $base->get('SESSION.uid')]);
        if ($dietData) {
            $base->set('diet', $dietData);
        }

        $base->set('title', 'Úprava profilu');
        $base->set('content', '/user/edit.html');
        echo \Template::instance()->render('index.html');
    }

    public function postEdit(\Base $base)
    {
        // Update user info
        $uzivatel = new \models\user();
        $uzivatel->load(["id=?", $base->get('SESSION.uid')]);

        $uzivatel->name = $base->get('POST.name');
        $uzivatel->surname = $base->get('POST.surname');
        $uzivatel->email = $base->get('POST.email');
        $uzivatel->date_of_birth = $base->get('POST.date_of_birth');

        if ($base->get('POST.phone')) {
            $uzivatel->phone_number = $base->get('POST.phone');
        }

        if ($base->get('POST.weight')) {
            $uzivatel->weight = $base->get('POST.weight');
        }

        if ($base->get('POST.medications')) {
            $uzivatel->medications = $base->get('POST.medications');
        }

        // Handle password change
        if ($base->get('POST.new_password') && $base->get('POST.current_password')) {
            if (password_verify($base->get('POST.current_password'), $uzivatel->password)) {
                $uzivatel->password = password_hash($base->get('POST.new_password'), PASSWORD_DEFAULT);
            } else {
                \Flash::instance()->addMessage('Špatné současné heslo', 'danger');
                $base->reroute('/user/edit');
                return;
            }
        }

        $uzivatel->save();

        // Update diet info
        $diet = new \models\diet();
        $dietData = $diet->findone(['user=?', $base->get('SESSION.uid')]);
        if (!$dietData) {
            $dietData = new \models\diet();
            $dietData->user = $base->get('SESSION.uid');
        }

        $dietData->alergies = $base->get('POST.alergies');
        $dietData->dietary_preferences = $base->get('POST.dietary_preferences');
        $dietData->dietary_restrictions = $base->get('POST.dietary_restrictions');
        $dietData->save();

        \Flash::instance()->addMessage('Profil aktualizován', 'success');
        $base->reroute('/user/profile');
    }
}