<?php

namespace Models;

class Order extends Model {

    public function getallOrders(): bool|array
    {
        $req = 'SELECT users.firstname, users.lastname, users.email, orderDate, status FROM orders 
        INNER JOIN users ON orders.users_id = users.id
        ORDER BY  orderDate DESC';
        return $this-> findAll($req);
    }
}