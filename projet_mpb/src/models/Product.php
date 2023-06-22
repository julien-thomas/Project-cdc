<?php

namespace Models;

class Product extends Model {

    // protected $table = 'products';
    /**
     * Returns all products in stock with a limit of 20 products
     * 
     * @return array
     */
    public function getAllProducts() :array {
        $req = 
        'SELECT products.id, name, price, title, picture, country, selected
        FROM products
        /*INNER JOIN categories ON categories.id = products.categories_id*/
        WHERE stock > 0
        ORDER BY price DESC
        LIMIT 20';
        return $this->findAll($req);
    }

    /**
     * Returns a selection of products
     * 
     * @param integer $id
     * @return array
     */
    public function getSelectedProducts() :null|array {
        $req = 
        "SELECT products.id, name, price, title, picture, country
        FROM products
        WHERE stock > 0 AND selected = '1'";
        return $this->findAll($req);
    }

    /**
     * Returns a product by its id
     * 
     * @param integer $id
     * @return array
     */
    public function getOneProduct($id) :null|array {
        $req = 'SELECT categories.category AS category, products.id, name, selected, description, stock, price, products.title, picture, country
        FROM products
        INNER JOIN categories ON products.categories_id = categories.id
        WHERE products.id = :id';
        //var_dump( $this->findOne($req, ["id" => $id, "status" => "on"]));
        return $this->findOne($req, ["id" => $id]);
    }

    public function setProduct($select, $id) {
        $newData = [
            'selected' => $select
        ];
        $val = $id;
        $this->updateOne('products', $newData, 'id', $val);
    }

    public function deleteProductById($product_id) {
        $this->deleteOne('products', 'id', $product_id);
    }

    

    public function addOneProduct(array $newProduct): false|int 
    {


        $data = [
            ':name'         => $newProduct['name'],
            ':title'        => $newProduct['title'],
            ':description'  => $newProduct['description'],
            ':stock'        => $newProduct['stock'],
            ':price'        => $newProduct['price'],
            ':grape'        => $newProduct['grape'],
            ':country'      => $newProduct['country'],
            ':vintage'      => $newProduct['vintage'],
            ':category'     => $newProduct['category'],
            ':picture'      => $newProduct['picture']
        ];
        
        return $this->addOne('products', 
        'name, title, description, stock, price, grape, country, vintage, categories_id, picture',
        ':name, :title, :description, :stock, :price, :grape, :country, :vintage, :category, :picture',
        $data);
        
    }
}