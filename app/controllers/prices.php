<?php

namespace controllers;

class prices
{
    public function getIndex(\Base $base)
    {
        $servicesModel = new \models\services();
        $services = $servicesModel->find(['1=1'], ['order' => 'name ASC']);

        $base->set('services', $services);
        $base->set('title', 'Ceník služeb');
        $base->set('content', 'home/prices.html');
        echo \Template::instance()->render('index.html');
    }

    public function getAdmin(\Base $base)
    {
        // Check if user is admin
        if (!$base->get('SESSION.uid') || $base->get('SESSION.role') !== '1') {
            \Flash::instance()->addMessage('Nemáte oprávnění k přístupu', 'danger');
            $base->reroute('/');
            return;
        }

        $servicesModel = new \models\services();
        $services = $servicesModel->find(['1=1'], ['order' => 'name ASC']);

        $base->set('services', $services);
        $base->set('title', 'Správa ceníku');
        $base->set('content', 'prices/admin.html');
        echo \Template::instance()->render('index.html');
    }

    public function postCreate(\Base $base)
    {
        if (!$base->get('SESSION.uid') || $base->get('SESSION.role') !== 'admin') {
            \Flash::instance()->addMessage('Nemáte oprávnění', 'danger');
            $base->reroute('/');
            return;
        }

        $service = new \models\services();
        $service->name = $base->get('POST.name');
        $service->description = $base->get('POST.description');
        $service->price = $base->get('POST.price');
        $service->save();

        \Flash::instance()->addMessage('Služba byla úspěšně přidána', 'success');
        $base->reroute('/prices/admin');
    }
}