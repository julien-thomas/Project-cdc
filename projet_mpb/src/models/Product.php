<?php

namespace Models;
require_once 'Model.php';
//use Models\Model;

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
        /* WHERE stock > 0 */
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
        WHERE stock > 0 AND selected = :selected";
        return $this->findAll($req, ['selected' => '1']);
    }

    /**
     * Returns a product by its id
     * 
     * @param integer $id
     * @return array
     */
    public function getOneProduct($id) :null|array {
        $req = 'SELECT categories.category AS category, categories_id, products.id, name, selected, `description`, stock, vintage, price, products.title, grape, picture, country
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

    public function updateOneProduct(array $newProduct, $product_id): void {

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

    public function getProductFromSearchbar($search) {

        $req = 'SELECT * FROM products WHERE `name` LIKE :find OR title LIKE :find OR vintage LIKE :find ORDER BY id DESC';
        return $this->findAll($req, [':find' => $search]);
    }

    /* public function getAllProductsFromCart() {

        var_dump($_COOKIE);

        if(isset($_COOKIE["shopping_cart"]))
        {
            $total = 0;
            $cookie_data = stripslashes($_COOKIE['shopping_cart']);
            $cart_data = json_decode($cookie_data, true);
            //$total = $total + ($values["item_quantity"] * $values["item_price"]);
        }
        var_dump($cart_data);
        var_dump($_COOKIE);
        /* $ids = implode(',',array_keys($_COOKIE['shopping_cart'], true));
        var_dump($ids);
        $req = 
        "SELECT products.id, name, price, picture
        FROM products
        WHERE id IN :ids";
        $this->findAll($req, ['ids' => $ids]); */
    //} */
}