<?php

namespace Controllers;

class Product extends Controller
{

    protected $modelName = \Models\Product::class;

    /**
     * @var FILE_EXT_IMG  extensions accepted for images
     */
    private const FILE_EXT_IMG = ['jpg', 'jpeg', 'gif', 'png'];

    /**
     * @var BASE_DIR Home directory of the site on the server disk
     */
    // define('BASE_DIR', realpath(dirname(__FILE__) . "/../"));

    /**
     * @var UPLOADS_DIR Directory where the files will be uploaded
     */
    private const UPLOADS_DIR = 'public/uploads/';


    /**
     * displays all the products from the database
     * 
     * @return void
     */
    public function showAll(): void
    {
        $products = $this->model->getAllProducts();
        \Apps\Renderer::render('product', 'layout', compact('products'));
    }

    /**
     * displays the product page
     * 
     * @return void
     */
    public function showOne(): void
    {
        $product_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id']))
            $product_id = $_GET['id'];
        if (!$product_id || !$this->model->getOneProduct($product_id)) {
            $_SESSION['error'] = 'Ce produit n\'existe pas';
            \Apps\Redirection::redirect('index.php?controller=home&task=showHomePage');
        } else {
            $model = new \Models\Opinion;
            $opinions = $model->getAllOpinionsByProduct($product_id);
            $product = $this->model->getOneProduct($product_id);
            \Apps\Renderer::render('productSheet', 'layout', compact('product', 'opinions'));
        }
    }

    /**
     * Displays all the reviews from a product
     * 
     * @return void
     */
    public function showAllOpinions(): void
    {
        $model = new \Models\Opinion;
        if (!empty($_GET['id']) && ctype_digit($_GET['id']))
            $product_id = $_GET['id'];
        if (!$product_id) {
            die("Ce produit n'a pas encore d'avis");
        }
        $opinions = $model->getAllOpinionsByProduct($product_id);
        \Apps\Renderer::render('productSheet', 'layout', compact('opinions'));
    }

    /** 
     * renames uploaded filename
     * 
     * @param array $file content of the $_FILES array at the index of the file to upload
     * @param null|array $errors the variable to contain the errors
     * @param string $folder absolute or relative path where the file will be moved (Default UPLOADS_DIR)
     * @param array $fileExtensions array of valid extensions (default FILE_EXT_IMG)
     * @return null|string un tableau contenant les erreurs ou vide
     */
    public function uploadFile(array $file, array &$errors, string $folder = self::UPLOADS_DIR, array $fileExtensions = self::FILE_EXT_IMG): null|string
    {
        $filename = '';
        if ($file["error"] === UPLOAD_ERR_OK) {
            $tmpName = $file["tmp_name"];

            // check if file extension is valid
            $tmpNameArray = explode(".", $file["name"]);
            $tmpExt = end($tmpNameArray);
            if (in_array($tmpExt, $fileExtensions)) {
                /* basename() can prevent filesystem attacks by removing possible directories in the name
                additional filename validation/sanitation may be appropriate
                Give the file a new name */
                $filename = uniqid() . '-' . basename($file["name"]); //example: 64a2f619a4214-Thuillac_2015.png
                if (!move_uploaded_file($tmpName, $folder . $filename)) {
                    $errors[] = 'Le fichier n\'a pas été enregistré correctement';
                }
            } else
                $errors[] = 'Ce type de fichier n\'est pas autorisé !';
        } else if ($file["error"] == UPLOAD_ERR_INI_SIZE || $file["error"] == UPLOAD_ERR_FORM_SIZE) {
            $errors[] = 'Le fichier est trop volumineux';
        } else {
            $errors[] = 'Une erreur a eu lieu au moment de l\'upload';
        }
        return $filename;
    }

    /**
     * add or update a product
     * 
     * @return void
     */
    public function addOrUpdateProduct(): void
    {
        $model = new \Models\Category;
        $categories = $model->getCategory();

        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (
                    !isset($_POST['token']) ||
                    !hash_equals($_SESSION['user']['token'], $_POST['token'])
                ) {
                    http_response_code(400);
                    exit('Request Forbidden');
                } else {

                    $errors = [];

                    $newProduct = [
                        'name'          => trim($_POST['name']),
                        'title'         => trim($_POST['title']),
                        'description'   => trim($_POST['description']),
                        'stock'         => trim($_POST['stock']),
                        'price'         => trim($_POST['price']),
                        'grape'         => trim($_POST['grape']),
                        'country'       => trim($_POST['country']),
                        'vintage'       => trim($_POST['vintage']),
                        'category'      => $_POST['category'], //'',
                        'picture'       => ''  // self::UPLOADS_DIR . $filename
                    ];

                    if (in_array('', $_POST))
                        $errors[] = 'Veuillez remplir tous les champs';

                    if (strlen($newProduct['name']) < 3 || strlen($newProduct['name']) > 50)
                        $errors[] = 'Le nom du produit doit comporter entre 3 et 50 caractères';
                    if (!preg_match('/^[A-Za-zÀ-ÿ0-9,.\'\s]+$/', $newProduct['name']))
                        $errors[] = 'Le nom du produit ne doit pas comporter caractères spéciaux';

                    if (strlen($newProduct['title']) < 3 || strlen($newProduct['title']) > 50)
                        $errors[] = 'L\'appellation doit comporter entre 3 et 50 caractères';
                    if (!preg_match('/^[A-Za-zÀ-ÿ,.\'\s]+$/', $newProduct['title']))
                        $errors[] = 'L\'appellation ne doit pas comporter de chiffres ou de caractères spéciaux';

                    if (strlen($newProduct['description']) < 3 || strlen($newProduct['description']) > 1000)
                        $errors[] = 'La description doit comporter entre 3 et 1000 caractères';

                    if (!ctype_digit($newProduct['stock']) || $newProduct['stock'] > 99999)
                        $errors[] = 'Le stock doit être composé de chiffres uniquement et être inférieur à 99999';

                    if (!preg_match('/^\d+(\.\d{1,2})?$/', $newProduct['price']))
                        $errors[] = 'Le prix doit être composé de chiffres avec 2 décimales maximum';

                    if (strlen($newProduct['grape']) < 3 || strlen($newProduct['grape']) > 50)
                        $errors[] = 'Le cépage doit comporter entre 3 et 50 caractères';
                    if (!preg_match('/^[A-Za-zÀ-ÿ,.\s]+$/', $newProduct['grape']))
                        $errors[] = 'Le cépage ne doit pas comporter de chiffres ou de caractères spéciaux';

                    if (strlen($newProduct['country']) < 3 || strlen($newProduct['country']) > 50)
                        $errors[] = 'Le pays doit comporter entre 3 et 50 caractères';
                    if (!preg_match('/^[A-Za-zÀ-ÿ,.\s]+$/', $newProduct['country']))
                        $errors[] = 'Le pays ne doit pas comporter de chiffres ou de caractères spéciaux';

                    if (!ctype_digit($newProduct['vintage']))
                        $errors[] = 'Le millésime doit être composé de chiffres uniquement';

                    if (strlen($newProduct['vintage']) != 4)
                        $errors[] = 'Le millésime doit être composé de  4 chiffres uniquement';

                    if (count($errors) === 0) {
                        if (array_key_exists('id', $_GET)) {
                            if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
                                $product_id = $_GET['id'];
                            }
                            if (!$product_id || !$this->model->getOneProduct($product_id)) {
                                $_SESSION['error'] = 'Ce produit n\'existe pas';
                                \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
                            } else {
                                $product = $this->model->getOneProduct($_GET['id']);
                                if ($_FILES['upload']['name'] === '') {
                                    $newProduct['picture'] = $product['picture'];
                                } else {
                                    $filename = $this->uploadFile($_FILES['upload'], $errors);
                                    if (count($errors) === 0) {
                                        $newProduct['picture'] = self::UPLOADS_DIR . $filename;
                                        unlink($product['picture']);
                                    } else {
                                        $_SESSION['error'] = $errors[0];
                                        \Apps\Redirection::redirect('index.php');
                                    }
                                }
                                $this->model->updateOneProduct($newProduct, $_GET['id']);
                                $_SESSION['success'] = "L'article a bien été modifié";
                                \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
                            }
                        } else {
                            $filename = $this->uploadFile($_FILES['upload'], $errors);
                            $newProduct['picture'] = self::UPLOADS_DIR . $filename;
                            if (count($errors) === 0) {
                                $this->model->addOneProduct($newProduct);
                                $_SESSION['success'] = "L'article a bien été ajouté";
                                \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
                            } else $_SESSION['error'] = $errors[0];
                        }
                    } else $_SESSION['error'] = $errors[0];
                }
            }
                if (array_key_exists('id', $_GET)) {
                    if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
                        $product_id = $_GET['id'];
                    }
                    if (!$product_id || !$this->model->getOneProduct($product_id)) {
                        $_SESSION['error'] = 'Ce produit n\'existe pas';
                        \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
                    } else {
                        $product = $this->model->getOneProduct($_GET['id']);
                        \Apps\Renderer::render('addProduct', 'admin', compact('categories', 'product'));
                    }
                } else {
                    \Apps\Renderer::render('addProduct', 'admin', compact('categories'));
                }
            
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * add a product to cart
     * 
     * @return void
     */
    public function addToCart(): void
    {

        if (isset($_POST["add_to_cart"])) {

            if (isset($_COOKIE["shopping_cart"])) {
                $cookie_data = stripslashes($_COOKIE['shopping_cart']);
                $cart_data = json_decode($cookie_data, true);
            } else {
                $cart_data = [];
            }

            $item_id_list = array_column($cart_data, 'item_id');
            if (isset($_GET["id"])) {
                $product_id = $_GET["id"];
                $model = new \Models\Product;
                if (!ctype_digit($_POST["quantity"]) || $_POST["quantity"] === '' || $_POST["quantity"] > $model->getOneProduct($product_id)['stock']) {
                    $_SESSION['error'] = 'Quantité non valide';
                    \Apps\Redirection::redirect('index.php?controller=product&task=showOne&id=' . $product_id);
                }

                if (in_array($product_id, $item_id_list)) {
                    foreach ($cart_data as $keys => $values) {
                        if ($cart_data[$keys]["item_id"] === $product_id) {
                            $cart_data[$keys]["item_quantity"] = $cart_data[$keys]["item_quantity"] + $_POST["quantity"];
                        }
                    }
                } else {
                    $item_array = [
                        'item_id'           => $product_id,
                        'item_quantity'     => trim($_POST["quantity"]),
                    ];
                    $cart_data[] = $item_array;
                }
            }


            $item_data = json_encode($cart_data, JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE encodes characters correctly
            setcookie('shopping_cart', $item_data, time() + (86400 * 30));
            \Apps\Redirection::redirect('index.php?controller=product&task=showCart');
        }

        /* remove products from cart */
        if ($_GET["action"] === "delete") {
            if (isset($_GET["id"])) {
                $cookie_data = stripslashes($_COOKIE['shopping_cart']);
                $cart_data = json_decode($cookie_data, true);
                foreach ($cart_data as $keys => $values) {
                    if ($cart_data[$keys]['item_id'] === $_GET["id"]) {
                        unset($cart_data[$keys]);
                        $item_data = json_encode($cart_data, JSON_UNESCAPED_UNICODE);
                        setcookie("shopping_cart", $item_data, time() + (86400 * 30));
                        \Apps\Redirection::redirect('index.php?controller=product&task=showCart');
                    }
                }
            } else {
                $_SESSION['error'] = 'Aucun produit sélectionné';
                \Apps\Redirection::redirect('index.php?controller=product&task=showCart');
            }
        }

        /* clear cart */
        if ($_GET["action"] === "clear") {
            setcookie("shopping_cart", "", time() - 3600);
            setcookie("totalQuantity", "", time() - 3600);
            \Apps\Redirection::redirect('index.php?controller=product&task=showCart');
        }
    }

    /**
     * display cart
     * 
     * @return void
     */
    public function showCart(): void
    {

        if (isset($_COOKIE["shopping_cart"])) {
            $total = 0;

            $cookie_data = stripslashes($_COOKIE['shopping_cart']);
            $cart_data = json_decode($cookie_data, true);
            $totalQuantity = 0;
            foreach ($cart_data as $keys => $values) {
                $product = $this->model->getOneProduct($cart_data[$keys]['item_id']);
                $cart_data[$keys]['item_name'] = $product['name'];
                $cart_data[$keys]['item_price'] = $product['price'];
                $cart_data[$keys]['item_picture'] = $product['picture'];
                $totalQuantity = $totalQuantity + $values["item_quantity"];
                setcookie('totalQuantity', $totalQuantity, time() + (86400 * 30));
                $total = $total + ($values["item_quantity"] * $product['price']);
            }
            if (isset($_GET['action'])) {
                if (isset($_SESSION['user'])) {
                    $newOrder =
                        [
                            'total_price' => $total,
                            'qty_total'   => $totalQuantity,
                            'users_id'    => $_SESSION['user']['id']
                        ];
                    if ($_GET['action'] === 'order') {
                        $model = new \Models\User;
                        $user = $model->getUser($_SESSION['user']['email']);
                        \Apps\Renderer::render('order', 'layout', compact('user', 'newOrder', 'cart_data'));
                    }

                    if ($_GET['action'] === 'validate') {
                        $model = new \Models\Order;
                        $id = $model->addOneOrder($newOrder);
                        $product = new \Models\Product;
                        foreach ($cart_data as $keys => $values) {
                            $orderDetail =
                                [
                                    'price'         => $cart_data[$keys]['item_price'],
                                    'qty'           => $cart_data[$keys]['item_quantity'],
                                    'orders_id'     => $id,
                                    'products_id'   => $cart_data[$keys]['item_id']
                                ];
                            $model->addOrderDetail($orderDetail);
                            $stock = $product->getOneProduct($orderDetail['products_id'])['stock'];
                            $newStock = $stock - $orderDetail['qty'];
                            $product->updateStock($newStock, $orderDetail['products_id']);
                        }

                        setcookie("shopping_cart", "", time() - 3600);
                        setcookie("totalQuantity", "", time() - 3600);
                        $_SESSION['success'] = 'Commande validée';
                        \Apps\Redirection::redirect('index.php?controller=product&task=showOrders');
                    }
                } else {
                    $_SESSION['error'] = 'Veuillez vous connecter pour commander';
                    \Apps\Redirection::redirect('index.php?controller=user&task=login');
                }
            } else {
                \Apps\Renderer::render('cart', 'layout', compact('cart_data', 'total'));
            }
        } else {
            \Apps\Renderer::render('cart', 'layout');
        }
    }

    /**
     * display orders from user
     * 
     * @return void
     */
    public function showOrders(): void
    {
        if (isset($_SESSION['user'])) {
            $model = new \Models\Order;
            $orders = $model->getAllOrdersById($_SESSION['user']['id']);
            \Apps\Renderer::render('myOrders', 'layout', compact('orders'));
        } else {
            $_SESSION['errors'] = 'Veuillez vous connecter pour voir vos commandes';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * display details from an order
     * 
     * @return void
     */
    public function showOrderDetails(): void
    {
        if (isset($_SESSION['user'])) {
            $order_id = null;
            if (array_key_exists('id', $_GET)) {
                if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
                    $order_id = $_GET['id'];
                    $model = new \Models\OrderDetails;
                    $orderDetails = $model->getOrderDetails($order_id);
                }
                if (!$order_id || !$model->getOrderDetails($order_id)) {
                    $_SESSION['error'] = 'Cette commande n\'existe pas';
                    \Apps\Redirection::redirect('index.php?controller=admin&task=showAllOrders');
                }
            }
            \Apps\Renderer::render('orderDetails', 'layout', compact('orderDetails'));
        } else {
            $_SESSION['errors'] = 'Veuillez vous connecter pour voir vos commandes';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * modifies status of an order
     * 
     * @return void
     */
    public function updateOrder(): void
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $order_id = null;
            if (array_key_exists('id', $_GET)) {
                if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
                    $order_id = $_GET['id'];
                    $model = new \Models\Order;
                    $order = $model->getOneOrder($order_id);
                }
                if (!$order_id || !$model->getOneOrder($order_id)) {
                    $_SESSION['error'] = 'Cette commande n\'existe pas';
                    \Apps\Redirection::redirect('index.php?controller=admin&task=showAllOrders');
                } else {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (
                            !isset($_POST['token']) ||
                            !hash_equals($_SESSION['user']['token'], $_POST['token'])
                        ) {
                            http_response_code(400);
                            exit('Request Forbidden');
                        } else {
                            $newStatus = $_POST['status'];
                            $model->updateOrder($newStatus, $order_id);
                            $_SESSION['success'] = 'Statut modifié';
                            \Apps\Redirection::redirect('index.php?controller=admin&task=showAllOrders');
                        }
                    }
                }
            }
            \Apps\Renderer::render('updateOrder', 'admin', compact('order'));
        } else {
            $_SESSION['errors'] = 'Veuillez vous connecter en tant qu\'admin';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }
}
