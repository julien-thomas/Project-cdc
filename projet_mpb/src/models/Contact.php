<?php

namespace Models;

class Contact extends Model {
    
    public function contactUS(array $contact) {
        $data = [
            ':mail' => $contact['mail'],
            ':subject' => $contact['subject'],
            ':content' => $contact['content']
        ];
        return $this->addOne('contacts', 'subject, email, content', ':subject, :mail, :content', $data);
    }

    public function getallContacts(): bool|array
    {
        $req = 'SELECT subject, email, content, created_at FROM contacts ORDER BY  created_at DESC';
        return $this-> findAll($req);
    }
}
