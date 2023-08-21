<?php

namespace Models;

class User extends Model {

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

    /**
     * Returns a user by id
     *
     * @param int $id
     * @return boolean|array
     */
    public function getUserbyId(int $id): bool|array
    {
        $req = 'SELECT id, blocked FROM users WHERE id = :id';
        return $this-> findOne($req, [':id' => $id]);
    }

    /**
     * Returns all users
     *
     * @return boolean|array
     */
    public function getallUsers(): bool|array
    {
        $req = 'SELECT id, firstname, lastname, address, zipCode, city, country, birthday, email, password, roles_id, blocked FROM users';
        return $this-> findAll($req);
    }

    /**
     * Adds a user
     *
     * @param array $newUser
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

    /**
     * Modifies a password
     *
     * @param string $password
     * @return void
     */
    public function newPassword(string $password): void
    {
        $newData = [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        $val = $_SESSION['user']['id'];
        $this->updateOne('users', $newData, 'id', $val);
    }

    /**
     * Modifies the status of a user
     *
     * @param string $status
     * @param int $user_id
     * @return void
     */
    public function setUser(string $status, int $user_id) {
        $newData = [
            'blocked' => $status
        ];
        $val = $user_id;
        $this->updateOne('users', $newData, 'id', $val);
    }
}

