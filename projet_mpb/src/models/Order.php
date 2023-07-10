<?php

namespace Models;

class Order extends Model {

    public function getallOrders(): bool|array
    {
        $req = 'SELECT orders.id, users.firstname, users.lastname, users.email, orderDate, status FROM orders 
        INNER JOIN users ON orders.users_id = users.id
        ORDER BY  orderDate DESC';
        return $this-> findAll($req);
    }

    public function addOneOrder(array $newOrder): false|int 
    {
        $data = [
            ':total_price'   => $newOrder['total_price'],
            ':qty_total'     => $newOrder['qty_total'],
            ':users_id'      => $newOrder['users_id']    
        ];
        
        return $this->addOne('orders', 'total_price, qty_total, users_id', ':total_price, :qty_total, :users_id', $data);
        
    }

    public function addOrderDetail(array $orderDetail): false|int
    {
        $data = [
            ':price'        => $orderDetail['price'],
            ':qty'          => $orderDetail['qty'],
            ':orders_id'    => $orderDetail['orders_id'],
            ':products_id'  => $orderDetail['products_id']  
        ];
        
        return $this->addOne('orders_details', 'price, qty, orders_id, products_id', ':price, :qty, :orders_id, :products_id', $data);
    }


    public function getAllOrdersById (int $id): bool|array
    {
        $req = 'SELECT users_id, total_price, qty_total, status, orderDate FROM orders
        WHERE users_id = :id';
        return $this->findAll($req, [':id' => $id]);
    }

}   