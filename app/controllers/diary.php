<?php

namespace controllers;

class diary
{
    public function getDiary(\Base $base)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        // Load diary entries for current user
        $diaryModel = new \models\diary();
        $entries = $diaryModel->find(['user=?', $base->get('SESSION.uid')], ['order' => 'created DESC']);

        $base->set('entries', $entries);
        $base->set('title', 'Můj deník');
        $base->set('content', 'diary/list.html');
        echo \Template::instance()->render('index.html');
    }

    public function getCreate(\Base $base)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        $base->set('title', 'Nový zápis do deníku');
        $base->set('content', 'diary/create.html');
        echo \Template::instance()->render('index.html');
    }

    public function postCreate(\Base $base)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        // Validate required fields
        if (!$base->get('POST.title') || !$base->get('POST.content')) {
            \Flash::instance()->addMessage('Vyplňte všechna povinná pole', 'danger');
            $base->reroute('/diary/create');
            return;
        }

        // Create new diary entry
        $diary = new \models\diary();
        $diary->user = $base->get('SESSION.uid');
        $diary->title = $base->get('POST.title');
        $diary->content = $base->get('POST.content');
        $diary->mood = $base->get('POST.mood');
        $diary->weight = $base->get('POST.weight');
        $diary->exercise = $base->get('POST.exercise');
        $diary->save();

        \Flash::instance()->addMessage('Zápis byl úspěšně vytvořen', 'success');
        $base->reroute('/diary');
    }

    public function getEdit(\Base $base, $params)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        $entryId = $params['id'];
        $diary = new \models\diary();
        $entry = $diary->findone(['id=? AND user=?', $entryId, $base->get('SESSION.uid')]);

        if (!$entry) {
            \Flash::instance()->addMessage('Zápis neexistuje nebo nemáte oprávnění', 'danger');
            $base->reroute('/diary');
            return;
        }

        $base->set('entry', $entry);
        $base->set('title', 'Upravit zápis');
        $base->set('content', 'diary/edit.html');
        echo \Template::instance()->render('index.html');
    }

    public function postEdit(\Base $base, $params)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        $entryId = $params['id'];
        $diary = new \models\diary();
        $entry = $diary->load(['id=? AND user=?', $entryId, $base->get('SESSION.uid')]);

        if (!$entry) {
            \Flash::instance()->addMessage('Zápis neexistuje nebo nemáte oprávnění', 'danger');
            $base->reroute('/diary');
            return;
        }

        // Validate required fields
        if (!$base->get('POST.title') || !$base->get('POST.content')) {
            \Flash::instance()->addMessage('Vyplňte všechna povinná pole', 'danger');
            $base->reroute('/diary/edit/' . $entryId);
            return;
        }

        // Update diary entry
        $diary->title = $base->get('POST.title');
        $diary->content = $base->get('POST.content');
        $diary->mood = $base->get('POST.mood');
        $diary->weight = $base->get('POST.weight');
        $diary->exercise = $base->get('POST.exercise');
        $diary->save();

        \Flash::instance()->addMessage('Zápis byl úspěšně aktualizován', 'success');
        $base->reroute('/diary');
    }

    public function getView(\Base $base, $params)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        $entryId = $params['id'];
        $diary = new \models\diary();
        $entry = $diary->findone(['id=? AND user=?', $entryId, $base->get('SESSION.uid')]);

        if (!$entry) {
            \Flash::instance()->addMessage('Zápis neexistuje nebo nemáte oprávnění', 'danger');
            $base->reroute('/diary');
            return;
        }

        $base->set('entry', $entry);
        $base->set('title', $entry->title);
        $base->set('content', 'diary/view.html');
        echo \Template::instance()->render('index.html');
    }

    public function getDelete(\Base $base, $params)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        $entryId = $params['id'];
        $diary = new \models\diary();
        $entry = $diary->load(['id=? AND user=?', $entryId, $base->get('SESSION.uid')]);

        if (!$entry) {
            \Flash::instance()->addMessage('Zápis neexistuje nebo nemáte oprávnění', 'danger');
            $base->reroute('/diary');
            return;
        }

        $diary->erase();
        \Flash::instance()->addMessage('Zápis byl smazán', 'success');
        $base->reroute('/diary');
    }
}