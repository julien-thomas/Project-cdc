<?php

namespace Controllers;

class Admin extends Controller
{

    protected $modelName = \Models\User::class;


    public function showAllProducts() {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $model = new \Models\Product;
            $products = $model->getAllProducts();
            \Renderer::render('adminProducts', 'admin', compact('products'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    public function showAllUsers() {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $users = $this->model->getAllUsers();
            \Renderer::render('adminUsers', 'admin', compact('users'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    public function showAllOpinions() {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $model = new \Models\Opinion;
            $opinions = $model->getAllOpinions();
            \Renderer::render('adminOpinions', 'admin', compact('opinions'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    public function showAllContacts() {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $model = new \Models\Contact;
            $contacts = $model->getAllContacts();
            \Renderer::render('adminContacts', 'admin', compact('contacts'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    public function showAllOrders() {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $model = new \Models\Order;
            $orders = $model->getAllOrders();
            \Renderer::render('adminOrders', 'admin', compact('orders'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Redirection::redirect('index.php?controller=user&task=login');
        }    
    }

    public function selectProduct() {
        $model = new \Models\Product;
        $product_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $product_id = $_GET['id'];
        }
        if (!$product_id || !$model->getOneProduct($product_id)) {
            $_SESSION['error'] = 'Ce produit n\'existe pas';
            /* header('Location:index.php?controller=admin&task=showAllProducts');
            exit; */
            \Redirection::redirect('index.php?controller=admin&task=showAllProducts');
        } else {
        /* if ($model->getOneProduct($product_id)['selected'] === 1)
            {
                $model->setProducts(0, $product_id);
            }
        else {
            $model->setProducts(1, $product_id);
        } */
        ($model->getOneProduct($product_id)['selected'] === 1) ? $model->setProduct(0, $product_id) : $model->setProduct(1, $product_id);
        //$products = $model->getAllProducts();
        /* header('Location:index.php?controller=admin&task=showAllProducts');
        exit; */
        \Redirection::redirect('index.php?controller=admin&task=showAllProducts');
        //\Renderer::render('adminProducts', 'admin', compact('products'));
        }
    }

    public function blockOpinion() {
        $model = new \Models\Opinion;
        $opinion_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $opinion_id = $_GET['id'];
        }
        if (!$opinion_id || !$model->getOneOpinion($opinion_id)) {
            $_SESSION['error'] = 'Cet avis n\'existe pas';
            /* header('Location:index.php?controller=admin&task=showAllOpinions');
            exit; */
            \Redirection::redirect('index.php?controller=admin&task=showAllOpinions');
        } else {
        ($model->getOneOpinion($opinion_id)['status'] === 'on') ? $model->setOpinion('off', $opinion_id) : $model->setOpinion('on', $opinion_id);
        //$opinions = $model->getAllOpinions();
        /* header('Location:index.php?controller=admin&task=showAllOpinions');
        exit; */
        \Redirection::redirect('index.php?controller=admin&task=showAllOpinions');
        //\Renderer::render('adminOpinions', 'admin', compact('opinions'));
        }
    }

    public function blockUser() {
        //$users = $this->model->getallUsers();
        //$email = null;
        $user_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $user_id = $_GET['id'];
        }
        //var_dump($users);
        //die;
        if (!$user_id || !$this->model->getUserbyId($user_id)) {
            $_SESSION['error'] = 'Cet utilisateur n\'existe pas';
            /* header('Location:index.php?controller=admin&task=showAllUsers');
            exit; */
            \Redirection::redirect('index.php?controller=admin&task=showAllUsers');
        } else {
        //$email = $users[$_GET['id']-1]['email'];
        ($this->model->getUserbyId($user_id)['blocked'] === 'false') ? $this->model->setUser('true', $user_id) : $this->model->setUser('false', $user_id);
        /* header('Location:index.php?controller=admin&task=showAllUsers');
        exit; */
        \Redirection::redirect('index.php?controller=admin&task=showAllUsers');
        //\Renderer::render('adminUsers', 'admin', compact('users'));
        }
    }

    public function processContact() {
        $model = new \Models\Contact;
        $contact_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $contact_id = $_GET['id'];
        }
        if (!$contact_id || !$model->getOneContact($contact_id)) {
            $_SESSION['error'] = 'Ce contact n\'existe pas';
            /* header('Location:index.php?controller=admin&task=showAllContacts');
            exit; */
            \Redirection::redirect('index.php?controller=admin&task=showAllContacts');
        } else {
        ($model->getOneContact($contact_id)['processed'] === 'on') ? $model->setContact('off', $contact_id) : $model->setContact('on', $contact_id);
        /* header('Location:index.php?controller=admin&task=showAllContacts');
        exit; */
        \Redirection::redirect('index.php?controller=admin&task=showAllContacts');
        }
    }

    public function deleteProduct() {
        $model = new \Models\Product;
        $product_id = trim($_POST['product_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['token']) ||
                !hash_equals($_SESSION['user']['token'], $_POST['token'])) {
                http_response_code(400);
                exit('Request Forbidden');
            } else {
                $model->deleteProductById($product_id);
            }
        }
        /* header('Location:index.php?controller=admin&task=showAllProducts');
        exit; */
        \Redirection::redirect('index.php?controller=admin&task=showAllProducts');
        //\Renderer::render('adminProducts', 'admin', compact('products'));
    }

    public function deleteContact() {
        $model = new \Models\Contact;
        $contact_id = trim($_POST['contact_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['token']) ||
                !hash_equals($_SESSION['user']['token'], $_POST['token'])) {
                http_response_code(400);
                exit('Request Forbidden');
            } else {
                $model->deleteContactById($contact_id);
            }
        }
        /* header('Location:index.php?controller=admin&task=showAllContacts');
        exit; */
        \Redirection::redirect('index.php?controller=admin&task=showAllContacts');
    }

    public function deleteOpinion() {
        $model = new \Models\Opinion;
        $opinion_id = trim($_POST['opinion_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['token']) ||
                !hash_equals($_SESSION['user']['token'], $_POST['token'])) {
                http_response_code(400);
                exit('Request Forbidden');
            } else {
                $model->deleteOpinionById($opinion_id);
            }
        }
        /* header('Location:index.php?controller=admin&task=showAllOpinions');
        exit; */
        \Redirection::redirect('index.php?controller=admin&task=showAllOpinions');
    }
    
}