<?php

namespace controllers;

class user
{
    public function postRegister(\Base $base)
    {
        $user = new \models\User();
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
        if($uz === false){
            \Flash::instance()->addMessage('Uživatel neexistuje', 'danger');
            $base->reroute('/login');
        }
        $login = new \models\login();
        $login->user = $uz;
        if(!password_verify($base->get('POST.password'), $uz->password)){
            \Flash::instance()->addMessage('Špatné heslo, nebo jmeno', 'danger');
            $login->save();
            $base->reroute('/login');
        }
        if($uz->approved == 0){
            \Flash::instance()->addMessage('Uživatel není schválen adminem', 'danger');
            $login->save();
            $base->reroute('/login');
        }
        $login->state = 1;
        $login->save();
        $base->set('SESSION.uid', $uz->id);
        $base->set('SESSION.username', $uz->username);
        \Flash::instance()->addMessage('Uživatel prihlášen', 'success');
        $base->reroute('/');
    }
    public function getLogout(\Base $base)
    {
        $base->clear('SESSION');
        \Flash::instance()->addMessage('Byl jsi uspesne odhlasen', 'success');
        $base->reroute('/');
    }
}