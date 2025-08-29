<?php

namespace controllers;

class review
{
    public function getIndex(\Base $base)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        // Load user's review if exists
        $reviewModel = new \models\review();
        $userReview = $reviewModel->findone(['user=?', $base->get('SESSION.uid')]);

        $base->set('review', $userReview);
        $base->set('title', 'Hodnocení trenéra');
        $base->set('content', 'review/index.html');
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

        // Check if user already has a review
        $reviewModel = new \models\review();
        $existingReview = $reviewModel->findone(['user=?', $base->get('SESSION.uid')]);

        if ($existingReview) {
            \Flash::instance()->addMessage('Už jste hodnocení vyplnili', 'warning');
            $base->reroute('/review');
            return;
        }

        $base->set('title', 'Vytvořit hodnocení');
        $base->set('content', 'review/create.html');
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

        // Check if user already has a review
        $reviewModel = new \models\review();
        $existingReview = $reviewModel->findone(['user=?', $base->get('SESSION.uid')]);

        if ($existingReview) {
            \Flash::instance()->addMessage('Už jste hodnocení vyplnili', 'warning');
            $base->reroute('/review');
            return;
        }

        // Validate rating
        $rating = $base->get('POST.rating');
        if (!$rating || $rating < 1 || $rating > 5) {
            \Flash::instance()->addMessage('Musíte vybrat hodnocení od 1 do 5 hvězd', 'danger');
            $base->reroute('/review/create');
            return;
        }

        // Validate title
        $title = $base->get('POST.title');
        if (!$title) {
            \Flash::instance()->addMessage('Název hodnocení je povinný', 'danger');
            $base->reroute('/review/create');
            return;
        }

        // Create new review
        $review = new \models\review();
        $review->user = $base->get('SESSION.uid');
        $review->rating = $rating;
        $review->title = $title;
        $review->review_text = $base->get('POST.review_text');
        $review->save();

        \Flash::instance()->addMessage('Hodnocení bylo úspěšně odesláno', 'success');
        $base->reroute('/review');
    }

    public function getEdit(\Base $base)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        // Load user's review
        $reviewModel = new \models\review();
        $userReview = $reviewModel->findone(['user=?', $base->get('SESSION.uid')]);

        if (!$userReview) {
            \Flash::instance()->addMessage('Nemáte žádné hodnocení k úpravě', 'warning');
            $base->reroute('/review');
            return;
        }

        $base->set('review', $userReview);
        $base->set('title', 'Upravit hodnocení');
        $base->set('content', 'review/edit.html');
        echo \Template::instance()->render('index.html');
    }

    public function postEdit(\Base $base)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        // Load user's review
        $reviewModel = new \models\review();
        $userReview = $reviewModel->findone(['user=?', $base->get('SESSION.uid')]);

        if (!$userReview) {
            \Flash::instance()->addMessage('Nemáte žádné hodnocení k úpravě', 'warning');
            $base->reroute('/review');
            return;
        }

        // Validate rating
        $rating = $base->get('POST.rating');
        if (!$rating || $rating < 1 || $rating > 5) {
            \Flash::instance()->addMessage('Musíte vybrat hodnocení od 1 do 5 hvězd', 'danger');
            $base->reroute('/review/edit');
            return;
        }

        // Validate title
        $title = $base->get('POST.title');
        if (!$title) {
            \Flash::instance()->addMessage('Název hodnocení je povinný', 'danger');
            $base->reroute('/review/edit');
            return;
        }

        // Update review
        $userReview->rating = $rating;
        $userReview->title = $title;
        $userReview->review_text = $base->get('POST.review_text');
        $userReview->save();

        \Flash::instance()->addMessage('Hodnocení bylo úspěšně aktualizováno', 'success');
        $base->reroute('/review');
    }

    public function getDelete(\Base $base)
    {
        // Check if user is logged in
        if (!$base->get('SESSION.uid')) {
            \Flash::instance()->addMessage('Musíte být přihlášeni', 'danger');
            $base->reroute('/login');
            return;
        }

        // Load user's review
        $reviewModel = new \models\review();
        $userReview = $reviewModel->findone(['user=?', $base->get('SESSION.uid')]);

        if (!$userReview) {
            \Flash::instance()->addMessage('Nemáte žádné hodnocení ke smazání', 'warning');
            $base->reroute('/review');
            return;
        }

        // Delete review
        $userReview->erase();
        \Flash::instance()->addMessage('Hodnocení bylo úspěšně smazáno', 'success');
        $base->reroute('/review');
    }

    public function getAll(\Base $base)
    {
        // Check if user is admin
        if (!$base->get('SESSION.uid') || $base->get('SESSION.role') !== 'admin') {
            \Flash::instance()->addMessage('Nemáte oprávnění k přístupu', 'danger');
            $base->reroute('/');
            return;
        }

        $reviewModel = new \models\review();
        $allReviews = $reviewModel->find(['1=1'], ['order' => 'created_at DESC']);

        $base->set('reviews', $allReviews);
        $base->set('title', 'Všechna hodnocení');
        $base->set('content', 'review/all.html');
        echo \Template::instance()->render('index.html');
    }
}