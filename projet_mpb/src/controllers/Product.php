<?php

namespace Controllers;

class Product extends Controller
{

    protected $modelName = \Models\Product::class; // ou "\Models\Product"



    public function showAll()
    {
        // $model = new $this->modelName;
        $products = $this->model->getAllProducts();
        \Renderer::render('product', 'layout', compact('products'));
    }



    public function showOne()
    {
        /*
        // On part du principe qu'on ne possède pas de param "id"
        $product_id = null;
*/
        // Mais si il y en a un et que c'est un nombre entier :
        // if (!empty($_GET['id']) && ctype_digit($_GET['id'])) 
        $product_id = $_GET['id'];

        /*
        // On peut désormais décider : erreur ou pas ?!
        if (!$product_id) {
            die("Vous devez préciser un paramètre `id` dans l'URL !");
        }
*/
        $model = new \Models\Opinion;
        $opinions = $model->getAllOpinionsByProduct($product_id);
        /**
         * Récupération de l'article en question
         */
        $product = $this->model->getOneProduct($product_id);
        \Renderer::render('productSheet', 'layout', compact('product', 'opinions'));
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
        \Renderer::render('productSheet', 'layout', compact('opinions'));
    }

    public function addOrUpdateProduct()
    {
        $model = new \Models\Category;
        $categories = $model->getCategory();

        if (array_key_exists('id', $_GET)) {
            $product = $this->model->getOneProduct($_GET['id']);
            //var_dump($product);
            \Renderer::render('addProduct', 'admin', compact('categories', 'product'));
        } else {
            \Renderer::render('addProduct', 'admin', compact('categories'));
        }
        /* if(ctype_digit($_GET['id'])) {
            $categories = $model->getCategory();
            $id = $_GET['id'];
            $product = $this->model->getOneProduct($id);
            \Renderer::render('addProduct', 'admin', compact('categories', 'product'));
        } */

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //var_dump($_POST);
            //var_dump($_FILES);

            // Tests upload
            $target_dir = 'uploads/';
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
            $newfilepath = 'public/uploads/' . $newname;

            $newProduct = [
                'name'          => trim($_POST['name']),
                'title'         => trim($_POST['title']),
                'description'   => trim($_POST['description']),
                'stock'         => trim($_POST['stock']),
                'price'         => trim($_POST['price']),
                'grape'         => trim($_POST['grape']),
                'country'       => trim($_POST['country']),
                'vintage'       => trim($_POST['vintage']),
                'category'      => ($_POST['category']), //'',
                'picture'       => $newfilepath
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

            $errors = [];

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
            if (!ctype_digit($newProduct['price']))
                $errors[] = 'Le prix doit être composé de chiffres uniquement';

            if (strlen($newProduct['grape']) < 3 || strlen($newProduct['grape']) > 50)
                $errors[] = 'Le cépage doit comporter entre 2 et 50 caractères';

            if (strlen($newProduct['country']) < 3 || strlen($newProduct['country']) > 50)
                $errors[] = 'Le pays doit comporter entre 2 et 50 caractères';

            if (!ctype_digit($newProduct['vintage']))
                $errors[] = 'Le millésime doit être composé de chiffres uniquement';

            // Test picture to do
            //if ($_FILES['upload']['error'] === UPLOAD_ERR_OK) 

            if ($_FILES['upload']['error'] === UPLOAD_ERR_NO_FILE)
                $errors[] = 'Aucun fichier envoyé';

            if ($_FILES['upload']['size'] > (1024 * 1024 * 1))
                $errors[] = 'La taille du fichier est supérieure à 1Mo';


            if (!move_uploaded_file($_FILES['upload']['tmp_name'], $newfilepath))
                $errors[] = 'Erreur : upload impossible';

            // var_dump($errors);
            if (!isset($_POST['token']) || !hash_equals($_SESSION['user']['token'], $_POST['token']))
                $errors[] = 'Requête interdite';

            if (count($errors) === 0) {
                if ($_FILES['upload']['error'] === UPLOAD_ERR_OK) {
                    if (array_key_exists('id', $_GET)) {
                        $this->model->updateOneProduct($newProduct, $_GET['id']);
                        $_SESSION['success'] = "L'article a bien été modifié";
                        if (headers_sent()) {
                            die("Redirect failed. Please click on this link: <a href='index.php?controller=admin&task=showAllProducts'>home</a>");
                        } else {
                            exit(header("Location:index.php?controller=admin&task=showAllProducts"));
                        }
                        //header('Location:index.php?controller=admin&task=showAllProducts');
                        //exit;
                    } else {
                        $this->model->addOneProduct($newProduct);
                        $_SESSION['success'] = "L'article a bien été ajouté";
                        header('Location:index.php?controller=admin&task=showAllProducts');
                        exit;
                    }
                } else $_SESSION['error'] = 'upload impossible';
            } else $_SESSION['error'] = $errors[0];
        }
    }

    public function addToCart()
    {

        if (isset($_POST["add_to_cart"])) {
            if (isset($_COOKIE["shopping_cart"])) {
                $cookie_data = stripslashes($_COOKIE['shopping_cart']);

                $cart_data = json_decode($cookie_data, true);
            } else {
                $cart_data = [];
            }

            $item_id_list = array_column($cart_data, 'item_id');

            if (in_array($_POST["hidden_id"], $item_id_list)) {
                foreach ($cart_data as $keys => $values) {
                    if ($cart_data[$keys]["item_id"] === $_POST["hidden_id"]) {
                        $cart_data[$keys]["item_quantity"] = $cart_data[$keys]["item_quantity"] + $_POST["quantity"];
                    }
                }
            } else {
                $item_array = [
                    'item_id'           => $_POST["hidden_id"],
                    'item_name'         => $_POST["hidden_name"],
                    'item_price'        => $_POST["hidden_price"],
                    'item_quantity'     => $_POST["quantity"],
                    'item_picture'      => $_POST["hidden_picture"]
                ];
                $cart_data[] = $item_array;
            }



            $item_data = json_encode($cart_data);
            setcookie('shopping_cart', $item_data, time() + (86400 * 30));
            //var_dump(json_decode($_COOKIE['shopping_cart']));
            //die;
            header("Location:index.php?controller=product&task=showCart");
        }

        /* remove products from cart */
        if (isset($_GET["id"])) {
            $cookie_data = stripslashes($_COOKIE['shopping_cart']);
            $cart_data = json_decode($cookie_data, true);
            foreach ($cart_data as $keys => $values) {
                if ($cart_data[$keys]['item_id'] === $_GET["id"]) {
                    unset($cart_data[$keys]);
                    $item_data = json_encode($cart_data);
                    setcookie("shopping_cart", $item_data, time() + (86400 * 30));
                    header("Location:index.php?controller=product&task=showCart");
                }
            }
        }

        /* clear cart */
        if ($_GET["action"] === "clear") {
            setcookie("shopping_cart", "", time() - 3600);
            setcookie("totalQuantity", "", time() - 3600);
            header("Location:index.php?controller=product&task=showCart");
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
            \Renderer::render('cart', 'layout', compact('cart_data'));
            //$total = $total + ($values["item_quantity"] * $values["item_price"]);
        } else {
            \Renderer::render('cart', 'layout');
        }
        //$products = $this->model->getAllProductsFromCart();

    }
}
