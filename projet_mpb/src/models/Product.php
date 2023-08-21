<?php

namespace Models;
require_once 'Model.php';

class Product extends Model {

    /**
     * Returns all products with a limit of 20 products
     * 
     * @return array
     */
    public function getAllProducts(): array {
        $req = 
        'SELECT products.id, name, price, title, picture, country, selected
        FROM products
        ORDER BY price DESC
        LIMIT 20';
        return $this->findAll($req);
    }

    /**
     * Returns a selection of products
     * 
     * @return null|array
     */
    public function getSelectedProducts(): null|array {
        $req = 
        "SELECT products.id, name, price, title, picture, country
        FROM products
        WHERE stock > 0 AND selected = :selected";
        return $this->findAll($req, ['selected' => '1']);
    }

    /**
     * Returns a product by its id
     * 
     * @param integer $id
     * @return bool|array
     */
    public function getOneProduct(int $id): bool|array {
        $req = 'SELECT categories.category AS category, categories_id, products.id, name, selected, `description`, stock, vintage, price, products.title, grape, picture, country
        FROM products
        INNER JOIN categories ON products.categories_id = categories.id
        WHERE products.id = :id';
        return $this->findOne($req, ["id" => $id]);
    }

    /**
     * Returns a product from orders by its id
     * 
     * @param integer $id
     * @return bool|array
     */
    public function getProductFromOrders(int $id) : bool|array {
        $req = 'SELECT products_id FROM orders_details WHERE products_id = :id';
        return $this->findOne($req, ['id' => $id]);
    }

    /**
     * sets the product's selected row to 0 or 1
     * 
     * @param integer $select
     * @param integer $id
     * @return void
     */
    public function setProduct(int $select, int $id): void {
        $newData = [
            'selected' => $select
        ];
        $val = $id;
        $this->updateOne('products', $newData, 'id', $val);
    }

    /**
     * delete the product by its id
     * 
     * @param integer $product_id
     * @return void
     */
    public function deleteProductById(int $product_id): void {
        $this->deleteOne('products', 'id', $product_id);
    }

    /**
     * Adds a product to the table products
     *
     * @param array $newProduct
     * @return false|int
     */
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

    /**
     * modifies a product
     * 
     * @param array $newProductd
     * @param int $product_id
     * @return void
     */
    public function updateOneProduct(array $newProduct, int $product_id): void {

        $newData = [
            'name'              => $newProduct['name'],
            'title'             => $newProduct['title'],
            'description'       => $newProduct['description'],
            'stock'             => $newProduct['stock'],
            'price'             => $newProduct['price'],
            'grape'             => $newProduct['grape'],
            'country'           => $newProduct['country'],
            'vintage'           => $newProduct['vintage'],
            'categories_id'     => $newProduct['category'],
            'picture'           => $newProduct['picture']
        ];

        $val = $product_id;

        $this->updateOne('products', $newData, 'id', $val);
    }

    /**
     * Returns a product by research
     * 
     * @param string $search
     * @return bool|array
     */
    public function getProductFromSearchbar(string $search): bool|array {

        $req = 'SELECT * FROM products WHERE `name` LIKE :find OR title LIKE :find OR vintage LIKE :find ORDER BY id DESC';
        return $this->findAll($req, [':find' => $search]);
    }

    /**
     * modifies the stock of a product
     * 
     * @param int $newStock
     * @param int $product_id
     * @return void
     */
    public function updateStock(int $newStock, int $product_id) {
        $newData = [
            'stock' => $newStock
        ];
        $val = $product_id;
        $this->updateOne('products', $newData, 'id', $val);
    }

}