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

        // Simple validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $base->set('error', 'Prosím vyplňte všechna povinná pole.');
            return $this->getContact($base);
        }

        $contact = new \models\contact();
        $contact->name = $name;
        $contact->email = $email;
        $contact->phone = $phone;
        $contact->subject = $subject;
        $contact->message = $message;
        $contact->time = date('Y-m-d H:i:s');
        $contact->save();

        $base->set('success', 'Děkujeme za vaši zprávu. Ozveme se vám co nejdříve.');
        return $this->getContact($base);
    }
}