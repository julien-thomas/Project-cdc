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
        $req = 'SELECT id, subject, email, content, created_at, processed FROM contacts ORDER BY  created_at DESC';
        return $this-> findAll($req);
    }

    public function getOneContact(int $id): bool|array
    {
        $req = 'SELECT id, processed FROM contacts
        WHERE id = :id';
        return $this-> findOne($req, [':id' => $id]);
    }

    public function setContact($processed, $id) {
        $newData = [
            'processed' => $processed
        ];
        $val = $id;
        $this->updateOne('contacts', $newData, 'id', $val);
    }
}
