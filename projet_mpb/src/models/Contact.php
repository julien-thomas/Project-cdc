<?php

namespace Models;

class Contact extends Model {
    
    /**
     * Adds a contact form to the database
     * 
     * @param array $contact
     * @return false|int
     */
    public function contactUS(array $contact): false|int {
        $data = [
            ':mail' => $contact['mail'],
            ':subject' => $contact['subject'],
            ':content' => $contact['content']
        ];
        return $this->addOne('contacts', 'subject, email, content', ':subject, :mail, :content', $data);
    }

    /**
     * Returns the list of all contactforms in a array
     * 
     * @return bool|array
     */
    public function getallContacts(): bool|array
    {
        $req = 'SELECT id, subject, email, content, created_at, processed FROM contacts ORDER BY  created_at DESC';
        return $this-> findAll($req);
    }

    /**
     * Returns one contact form by its id
     * 
     * @param int $id
     * @return bool|array
     */
    public function getOneContact(int $id): bool|array
    {
        $req = 'SELECT id, processed FROM contacts
        WHERE id = :id';
        return $this-> findOne($req, [':id' => $id]);
    }

    /**
     * sets the contact form to processed or not
     * 
     * @param string $processed
     * @param int $id
     * @return void
     */
    public function setContact(string $processed, int $id): void {
        $newData = [
            'processed' => $processed
        ];
        $val = $id;
        $this->updateOne('contacts', $newData, 'id', $val);
    }

    /**
     * delete the contact form by its id
     * 
     * @param int $id
     * @return void
     */
    public function deleteContactById(int $contact_id): void {
        $this->deleteOne('contacts', 'id', $contact_id);
    }
}
