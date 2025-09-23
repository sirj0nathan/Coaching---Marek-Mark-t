<?php

namespace controllers;
class index
{
    public function index(\Base $base)
    {
        echo \Template::instance()->render('index.html');
    }

    public function getAbout(\Base $base)
    {
        // Load recent reviews for display
        $reviewModel = new \models\review();
        $reviews = $reviewModel->find(['1=1'], ['order' => 'RAND()', 'limit' => 6]);

        // Always set the reviews variable, even if it's empty
        $base->set('reviews', $reviews ?: []);

        $base->set('title', 'O nás');
        $base->set('content', '/home/index.html');
        echo \Template::instance()->render('index.html');
    }
}