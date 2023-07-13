<?php

namespace Models;

class OrderDetails extends Model {


public function getOrderDetails($order_id) {
            $req = 'SELECT orders.id, orders_details.id, status, orderDate, qty, orders_details.price, products.name FROM orders_details 
            INNER JOIN orders ON orders.id = orders_id
            INNER JOIN products ON products.id = products_id
            WHERE orders.id = :id';
            return $this->findAll($req, [':id' => $order_id]);
        }

}