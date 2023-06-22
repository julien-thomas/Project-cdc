<?php

namespace Models;

class User extends Model {

    // protected $table = 'users';

    /**
     * Find user by email
     *
     * @param string $email
     * @return boolean|array
     */
    public function getUser(string $email): bool|array
    {
        $req = 'SELECT id, firstname, lastname, address, zipCode, city, country, birthday, email, password, roles_id, blocked FROM users WHERE email = :mail';
        return $this-> findOne($req, [':mail' => $email]);
    }

    public function getallUsers(): bool|array
    {
        $req = 'SELECT id, firstname, lastname, address, zipCode, city, country, birthday, email, password, roles_id, blocked FROM users';
        return $this-> findAll($req);
    }

    /**
     * Add a user
     *
     * @param array $form
     * @return false|int
     */
    public function addUser(array $newUser): false|int
    {
        $data = [
            ':mail'      => $newUser['mail'],
            ':firstname' => $newUser['firstname'],
            ':lastname'  => $newUser['lastname'],
            ':birthday'  => $newUser['birthday'],
            ':address'   => $newUser['address'],
            ':zipCode'   => $newUser['zipCode'],
            ':city'      => $newUser['city'],
            ':country'   => $newUser['country'],
            ':password'  => password_hash($newUser['password'], PASSWORD_DEFAULT)
        ];
        return $this->addOne('users', 
        'email, firstname, lastname, birthday, address, zipCode, city, country, password',
        ':mail, :firstname, :lastname, :birthday, :address, :zipCode, :city, :country, :password',
        $data);
    }

    public function newPassword(string $password): void
    {
        var_dump($_SESSION);
        $newData = [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        $val = $_SESSION['user']['id'];
        $this->updateOne('users', $newData, 'id', $val);
        var_dump($val);
    }

    public function setUser($status, $email) {
        $newData = [
            'blocked' => $status
        ];
        $val = $email;
        $this->updateOne('users', $newData, 'email', $val);
    }
}

