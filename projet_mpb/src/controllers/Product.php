<?php

namespace Controllers;

class Product extends Controller
{

    protected $modelName = \Models\Product::class; // ou "\Models\Product"

    /**
     * @var FILE_EXT_IMG  extensions acceptées pour les images
     */
    private const FILE_EXT_IMG = ['jpg', 'jpeg', 'gif', 'png'];

    /**
     * @var BASE_DIR Répertoire de base du blog sur le disque du serveur
     */
    // define('BASE_DIR', realpath(dirname(__FILE__) . "/../"));

    /**
     * @var UPLOADS_DIR Répertoire ou seront uploadés les fichiers
     */
    private const UPLOADS_DIR = 'public/uploads/';


    public function showAll()
    {
        // $model = new $this->modelName;
        $products = $this->model->getAllProducts();
        \Apps\Renderer::render('product', 'layout', compact('products'));
    }



    public function showOne()
    {

        // On part du principe qu'on ne possède pas de param "id"
        $product_id = null;

        // Mais si il y en a un et que c'est un nombre entier :
        if (!empty($_GET['id']) && ctype_digit($_GET['id']))
            $product_id = $_GET['id'];

        // On peut désormais décider : erreur ou pas ?!
        if (!$product_id || !$this->model->getOneProduct($product_id)) {
            $_SESSION['error'] = 'Ce produit n\'existe pas';
            /* header('Location:index.php?controller=home&task=showHomePage');
            exit; */
            \Apps\Redirection::redirect('index.php?controller=home&task=showHomePage');
        } else {

            $model = new \Models\Opinion;
            $opinions = $model->getAllOpinionsByProduct($product_id);
            /**
             * Récupération de l'article en question
             */
            $product = $this->model->getOneProduct($product_id);
            \Apps\Renderer::render('productSheet', 'layout', compact('product', 'opinions'));
        }
    }

    public function showAllOpinions()
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

    /** Déplace un fichier transmis dans un répertoire du serveur
     * @param $file contenu du tableau $_FILES à l'index du fichier à uploader
     * @param $errors la variable devant contenir les erreurs. Passage par référence ;)
     * @param $folder chemin absolue ou relatif où le fichier sera déplacé. Par default UPLOADS_DIR
     * @param $fileExtensions par defaut vaut FILE_EXT_IMG. un tableau d'extensions valident
     * @return array un tableau contenant les erreurs ou vide
     */
    public function uploadFile(array $file, array &$errors, string $folder = self::UPLOADS_DIR, array $fileExtensions = self::FILE_EXT_IMG)
    {
        $filename = '';
        //var_dump($_FILES);
        if ($file["error"] === UPLOAD_ERR_OK) {
            $tmpName = $file["tmp_name"];

            // On récupère l'extension du fichier pour vérifier si elle est dans  $fileExtensions
            $tmpNameArray = explode(".", $file["name"]);
            $tmpExt = end($tmpNameArray);
            if (in_array($tmpExt, $fileExtensions)) {
                // basename() peut empêcher les attaques de système de fichiers en supprimant les éventuels répertoire dans le nom
                // la validation/assainissement supplémentaire du nom de fichier peut être appropriée
                // On donne un nouveau nom au fichier
                $filename = uniqid() . '-' . basename($file["name"]);
                if (!move_uploaded_file($tmpName, $folder . $filename)) {
                    $errors[] = 'Le fichier n\'a pas été enregistré correctement';
                }
            } else
                $errors[] = 'Ce type de fichier n\'est pas autorisé !';
        } else if ($file["error"] == UPLOAD_ERR_INI_SIZE || $file["error"] == UPLOAD_ERR_FORM_SIZE) {
            //fichier trop volumineux
            $errors[] = 'Le fichier est trop volumineux';
        } else {
            $errors[] = 'Une erreur a eu lieu au moment de l\'upload';
        }

        return $filename;
    }

    public function addOrUpdateProduct()
    {
        $model = new \Models\Category;
        $categories = $model->getCategory();


        /* if(ctype_digit($_GET['id'])) {
            $categories = $model->getCategory();
            $id = $_GET['id'];
            $product = $this->model->getOneProduct($id);
            \Renderer::render('addProduct', 'admin', compact('categories', 'product'));
        } */
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                var_dump($_POST);
                //var_dump($_FILES);

                // Tests upload
                /* $target_dir = 'uploads/';
            $target_file = $target_dir . basename($_FILES["upload"]["name"]);
            $allowed = ['jpg', 'jpeg', 'gif', 'png'];
            $filetype = $_FILES['upload']['type'];
            $filename = $_FILES['upload']['name'];
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowed))
                $errors[] = 'L\'extension du fichier n\'est pas valide';
            if (!empty($_FILES['upload']['tmp_name'])) {
                $check = getimagesize($_FILES['upload']['tmp_name']);
                if (!$check)
                    $errors[] = 'Le fichier n\'est pas une image';
            } else {
                $errors[] = 'Veuillez sélectionner une image';
            }

            $newname = md5(uniqid()) . '.' . $extension;
            $newfilepath = 'public/uploads/' . $newname; */
                $errors = [];

                //$filename = $this->uploadFile($_FILES['upload'], $errors);

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

                /*  switch($_POST['category']) {
                case 'Vin rouge':
                    $newProduct['category'] = 1;
                break;
                case 'Vin rosé':
                    $newProduct['category'] = 2;
                break;
                case 'Vin blanc':
                    $newProduct['category'] = 3;
                break;
            }
             */



                if (in_array('', $_POST))
                    $errors[] = 'Veuillez remplir tous les champs';

                if (strlen($newProduct['name']) < 3 || strlen($newProduct['name']) > 50)
                    $errors[] = 'Le nom doit comporter entre 2 et 50 caractères';

                if (strlen($newProduct['title']) < 3 || strlen($newProduct['title']) > 50)
                    $errors[] = 'Le nom doit comporter entre 2 et 50 caractères';

                if (strlen($newProduct['name']) < 3 || strlen($newProduct['name']) > 50)
                    $errors[] = 'Le nom doit comporter entre 2 et 50 caractères';

                if (!ctype_digit($newProduct['stock']) || $newProduct['stock'] > 99999)
                    $errors[] = 'Le stock doit être composé de chiffres uniquement et être inférieur à 99999';
                // complete test price
                if (!is_numeric($newProduct['price']))
                    $errors[] = 'Le prix doit être composé de chiffres avec 2 décimales maximum';

                if (strlen($newProduct['grape']) < 3 || strlen($newProduct['grape']) > 50)
                    $errors[] = 'Le cépage doit comporter entre 2 et 50 caractères';

                if (strlen($newProduct['country']) < 3 || strlen($newProduct['country']) > 50)
                    $errors[] = 'Le pays doit comporter entre 2 et 50 caractères';

                if (!ctype_digit($newProduct['vintage']))
                    $errors[] = 'Le millésime doit être composé de chiffres uniquement';


                var_dump($errors);
                if (!isset($_POST['token']) || !hash_equals($_SESSION['user']['token'], $_POST['token']))
                    $errors[] = 'Requête interdite';
                var_dump($_FILES);

                if (count($errors) === 0) {
                    if (array_key_exists('id', $_GET)) {
                        var_dump($_FILES);
                        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
                            $product_id = $_GET['id'];
                        }
                        if (!$product_id || !$this->model->getOneProduct($product_id)) {
                            $_SESSION['error'] = 'Ce produit n\'existe pas';
                            /* header('Location:index.php?controller=admin&task=showAllProducts');
                            exit; */
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
                                    /* header('Location:index.php');
                                    exit; */
                                    \Apps\Redirection::redirect('index.php');
                                }
                            }
                            $this->model->updateOneProduct($newProduct, $_GET['id']);
                            $_SESSION['success'] = "L'article a bien été modifié";
                            /* header('Location:index.php?controller=admin&task=showAllProducts');
                            exit; */
                            \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
                        }
                    } else {
                        $filename = $this->uploadFile($_FILES['upload'], $errors);
                        $newProduct['picture'] = self::UPLOADS_DIR . $filename;
                        if (count($errors) === 0) {
                            $this->model->addOneProduct($newProduct);
                            $_SESSION['success'] = "L'article a bien été ajouté";
                            /* header('Location:index.php?controller=admin&task=showAllProducts');
                                exit; */
                            \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
                        } else $_SESSION['error'] = $errors[0];
                    }
                } else $_SESSION['error'] = $errors[0];
            }

            if (array_key_exists('id', $_GET)) {
                if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
                    $product_id = $_GET['id'];
                }
                if (!$product_id || !$this->model->getOneProduct($product_id)) {
                    $_SESSION['error'] = 'Ce produit n\'existe pas';
                    /* header('Location:index.php?controller=admin&task=showAllProducts');
                    exit; */
                    \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
                } else {
                    $product = $this->model->getOneProduct($_GET['id']);
                    //var_dump($product);
                    //var_dump($_POST);
                    //var_dump($_SERVER);
                    \Apps\Renderer::render('addProduct', 'admin', compact('categories', 'product'));
                }
            } else {
                \Apps\Renderer::render('addProduct', 'admin', compact('categories'));
            }
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    public function addToCart()
    {

        if (isset($_POST["add_to_cart"])) {
            var_dump($_COOKIE);

            if (isset($_COOKIE["shopping_cart"])) {
                $cookie_data = stripslashes($_COOKIE['shopping_cart']);

                $cart_data = json_decode($cookie_data, true);
            } else {
                $cart_data = [];
            }

            $item_id_list = array_column($cart_data, 'item_id');
            var_dump($item_id_list);
            if (isset($_GET["id"])) {
                $product_id = $_GET["id"];
                $model = new \Models\Product;
                if (!ctype_digit($_POST["quantity"]) || $_POST["quantity"] === '' || $_POST["quantity"] > $model->getOneProduct($product_id)['stock']) {
                    $_SESSION['error'] = 'Quantité non valide';
                    /* header('Location: index.php?controller=product&task=showOne&id=' . $product_id); */
                    \Apps\Redirection::redirect('index.php?controller=product&task=showOne&id=' . $product_id);
                }
                //if (in_array($_POST["hidden_id"], $item_id_list)) {
                if (in_array($product_id, $item_id_list)) {
                    foreach ($cart_data as $keys => $values) {
                        if ($cart_data[$keys]["item_id"] === $product_id) {
                            $cart_data[$keys]["item_quantity"] = $cart_data[$keys]["item_quantity"] + $_POST["quantity"];
                        }
                    }
                } else {
                    $item_array = [
                        'item_id'           => $product_id, //$_POST["hidden_id"],
                        'item_quantity'     => trim($_POST["quantity"]),
                        //'item_name'         => '', //$product['name'], //$_POST["hidden_name"],
                        //'item_price'        => '', //$product['price'], //$_POST["hidden_price"],
                        //'item_picture'      => '' //$product['picture'] //$_POST["hidden_picture"]
                    ];
                    $cart_data[] = $item_array;
                }
            }


            $item_data = json_encode($cart_data, JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE encodes characters correctly
            setcookie('shopping_cart', $item_data, time() + (86400 * 30));
            //var_dump(json_decode($_COOKIE['shopping_cart']));
            //die;
            /* header("Location:index.php?controller=product&task=showCart"); */
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
                        $item_data = json_encode($cart_data, JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE encodes characters correctly
                        setcookie("shopping_cart", $item_data, time() + (86400 * 30));
                        /* header("Location:index.php?controller=product&task=showCart"); */
                        \Apps\Redirection::redirect('index.php?controller=product&task=showCart');
                    }
                }
            } else {
                $_SESSION['error'] = 'Aucun produit sélectionné';
                /* header('Location:index.php?controller=product&task=showCart');
                exit; */
                \Apps\Redirection::redirect('index.php?controller=product&task=showCart');
            }
        }

        /* clear cart */
        if ($_GET["action"] === "clear") {
            setcookie("shopping_cart", "", time() - 3600);
            setcookie("totalQuantity", "", time() - 3600);
            /* header("Location:index.php?controller=product&task=showCart"); */
            \Apps\Redirection::redirect('index.php?controller=product&task=showCart');
        }





        /*  if(!isset($_COOKIE['panier'])) {
            $_COOKIE['panier'] = [];
        }
 */

        /* if(isset($_GET['id'])) {
            $product_id = $_GET['id'];
        }
        $cart = [];
 */
        /* if(isset($_COOKIE['panier'][$product_id]))
            $_COOKIE['panier'][$product_id]++; */
        //else {
        // setcookie('cart[$product_id]', 1, time() + 2 * 24 * 3600, '/');
        //$_COOKIE['panier'] = [$product_id => 1];

        //}
        //$newCart = unserialize(($_COOKIE['cart']));
        //$_COOKIE['panier'] = json_decode($_COOKIE['panier'], true);
        //var_dump($_COOKIE);
        //var_dump($newCart);
        //die;
        /* header('Location:index.php?controller=product&task=showCart');
        exit; */
    }

    public function showCart()
    {

        if (isset($_COOKIE["shopping_cart"])) {
            $total = 0;

            $cookie_data = stripslashes($_COOKIE['shopping_cart']);
            $cart_data = json_decode($cookie_data, true);
            var_dump($cart_data);
            $totalQuantity = 0;
            foreach ($cart_data as $keys => $values) {
                /* if ($cart_data[$keys]["item_id"] === $product_id) {
                    $cart_data[$keys]["item_quantity"] = $cart_data[$keys]["item_quantity"] + $_POST["quantity"]; */
                $product = $this->model->getOneProduct($cart_data[$keys]['item_id']);
                $cart_data[$keys]['item_name'] = $product['name'];
                $cart_data[$keys]['item_price'] = $product['price'];
                $cart_data[$keys]['item_picture'] = $product['picture'];
                //$cart_data[] = $item_array;
                $totalQuantity = $totalQuantity + $values["item_quantity"];
                var_dump($totalQuantity);
                setcookie('totalQuantity', $totalQuantity, time() + (86400 * 30));
                $total = $total + ($values["item_quantity"] * $product['price']);
                var_dump($total);
                /* $item_array = [
                'item_id'           =>
                'item_name'         => $product['name'], 
                'item_price'        => $product['price'],
                'item_picture'      => $product['picture']
            ];  */
            }
            if (isset($_GET['action'])) {
                if (isset($_SESSION['user'])) {
                    $newOrder =
                        [
                            'total_price' => $total,
                            'qty_total'   => $totalQuantity,
                            'users_id'    => $_SESSION['user']['id']
                        ];
                    var_dump($newOrder);
                    if ($_GET['action'] === 'order') {
                        //if (isset($_SESSION['user'])) {
                        var_dump($_SESSION);
                        var_dump($cart_data);

                        $model = new \Models\User;
                        $user = $model->getUser($_SESSION['user']['email']);
                        // $this->validateOrder($newOrder);

                        \Apps\Renderer::render('order', 'layout', compact('user', 'newOrder', 'cart_data'));
                        /* \Renderer::render('order', 'layout', compact('user')); */
                        /* 
                         */

                        /* $orders = $model->getAllOrdersById($_SESSION['user']['id']); */
                        /* $model = new \Models\User;
                        $user = $model->getUser($_SESSION['user']['email']); */
                        /* \Redirection::redirect('index.php?controller=product&task=showOrders'); */
                        /* \Renderer::render('myOrders', 'layout', compact('orders')); */
                        /* } else {
                        $_SESSION['error'] = 'Veuillez vous connecter pour commander';
                        \Redirection::redirect('index.php?controller=user&task=login'); */
                    }

                    if ($_GET['action'] === 'validate') {
                        //if (isset($_SESSION['user'])) {
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
                            var_dump($stock);
                            $newStock = $stock - $orderDetail['qty'];
                            $product->updateStock($newStock, $orderDetail['products_id']);
                        }

                        setcookie("shopping_cart", "", time() - 3600);
                        setcookie("totalQuantity", "", time() - 3600);
                        $_SESSION['success'] = 'Commande validée';
                        \Apps\Redirection::redirect('index.php?controller=product&task=showOrders');
                        /* }  else {
                        $_SESSION['error'] = 'Veuillez vous connecter pour commander';
                        \Redirection::redirect('index.php?controller=user&task=login');
                    } */
                    }
                } else {
                    $_SESSION['error'] = 'Veuillez vous connecter pour commander';
                    \Apps\Redirection::redirect('index.php?controller=user&task=login');
                }
            } else {
                var_dump($cart_data);
                var_dump($_COOKIE);
                //die;
                \Apps\Renderer::render('cart', 'layout', compact('cart_data', 'total'));
                //$total = $total + ($values["item_quantity"] * $values["item_price"]);
            }
        } else {
            \Apps\Renderer::render('cart', 'layout');
        }
        //$products = $this->model->getAllProductsFromCart();
    }


    /* public function validateOrder()
    {
        /*  if (isset($_GET['action'])) {
            if ($_GET['action'] === 'validate') {
                if (isset($_SESSION['user'])) {
                    $model = new \Models\Order;
                    $model->addOneOrder($newOrder);
                    setcookie("shopping_cart", "", time() - 3600);
                    setcookie("totalQuantity", "", time() - 3600);
                    $_SESSION['success'] = 'Commande validée';
                    \Redirection::redirect('index.php?controller=product&task=showOrders');
                } else {
                    $_SESSION['error'] = 'Veuillez vous connecter pour commander';
                    \Redirection::redirect('index.php?controller=user&task=login');
                }
            } else {
                $_SESSION['error'] = 'Veuillez valider votre commande';
                \Redirection::redirect('index.php?controller=product&task=showCart&action=order');
            }
        }
        else {
            $_SESSION['error'] = 'Veuillez valider votre commande';
            \Redirection::redirect('index.php?controller=product&task=showCart&action=order');
        } */
    /* $model = new \Models\User;
        $user = $model->getUser($_SESSION['user']['email']);
        \Renderer::render('order', 'layout', compact('user', 'newOrder')); */
    /* if (isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['token']) ||
                !hash_equals($_SESSION['user']['token'], $_POST['token'])) {
                http_response_code(400);
                exit('Request Forbidden');
                } else {
                    $model = new \Models\Order;
                    $newOrder =
                            [
                                'total_price' => $_POST['price'],
                                'qty_total'   => $_POST['quantity'],
                                'users_id'    => $_SESSION['user']['id']
                            ];
                    $model->addOneOrder($newOrder);
                    setcookie("shopping_cart", "", time() - 3600);
                    setcookie("totalQuantity", "", time() - 3600);
                    $_SESSION['success'] = 'Commande validée';
                    \Redirection::redirect('index.php?controller=product&task=showOrders');
                }
            }
        } else {
        $_SESSION['error'] = 'Veuillez vous connecter pour commander';
        \Redirection::redirect('index.php?controller=user&task=login');
        }
    } */

    public function showOrders()
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

    public function showOrderDetails()
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
        }
        else {
            $_SESSION['errors'] = 'Veuillez vous connecter pour voir vos commandes';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    public function updateOrder()
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
    /* public function getProductFromAjax() {
        $content = file_get_contents("php://input");
        $data = json_decode($content, true);
        $search = "%".$data['textToFind']."%";
        $this->model->getProductFromSearchbar($search);
    } */

    /* public function addOrder() {
        $model = new \Models\User;
        var_dump($_SESSION);
        $user = $model->getUser($_SESSION['user']['email']);

        $cookie_data = stripslashes($_COOKIE['shopping_cart']);
        $cart_data = json_decode($cookie_data, true);
        var_dump($cart_data);

        \Renderer::render('order', 'layout', compact('user'));
    } */
}
