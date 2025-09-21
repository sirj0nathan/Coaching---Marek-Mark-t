<?php

namespace controllers;

class contact
{
    public function getContact(\Base $base)
    {
        $base->set('title', 'Kontakt');
        $base->set('content', 'home/contact.html');
        echo \Template::instance()->render('index.html');
    }

    public function postContact(\Base $base)
    {
        $name = $base->get('POST.name');
        $email = $base->get('POST.email');
        $phone = $base->get('POST.phone');
        $subject = $base->get('POST.subject');
        $message = $base->get('POST.message');

        $contact = new \models\contact();
        $contact->name = $name;
        $contact->email = $email;
        $contact->phone = $phone;
        $contact->subject = $subject;
        $contact->message = $message;
        $contact->time = date('Y-m-d H:i:s');
        $contact->save();

        \Flash::instance()->addMessage('Zpráva byla úspěšně odeslána. Ozveme se Vám co nejdříve.', 'success');
        $base->reroute('/contact');
    }
}