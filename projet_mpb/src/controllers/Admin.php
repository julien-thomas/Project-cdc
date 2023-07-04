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
            header('Location: index.php?controller=user&task=login');
            exit;
        }
    }

    public function showAllUsers() {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $users = $this->model->getAllUsers();
            \Renderer::render('adminUsers', 'admin', compact('users'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            // Redirection vers login
            header('Location: index.php?controller=user&task=login');
            exit;
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
            header('Location: index.php?controller=user&task=login');
            exit;
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
            header('Location: index.php?controller=user&task=login');
            exit;
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
            header('Location: index.php?controller=user&task=login');
            exit;
        }    
    }

    public function selectProduct() {
        $model = new \Models\Product;
        $product_id = $_GET['id'];
        /* if ($model->getOneProduct($product_id)['selected'] === 1)
            {
                $model->setProducts(0, $product_id);
            }
        else {
            $model->setProducts(1, $product_id);
        } */
        ($model->getOneProduct($product_id)['selected'] === 1) ? $model->setProduct(0, $product_id) : $model->setProduct(1, $product_id);
        //$products = $model->getAllProducts();
        header('Location:index.php?controller=admin&task=showAllProducts');
        exit;
        //\Renderer::render('adminProducts', 'admin', compact('products'));
    }

    public function blockOpinion() {
        $model = new \Models\Opinion;
        $opinion_id = $_GET['id'];
        ($model->getOneOpinion($opinion_id)['status'] === 'on') ? $model->setOpinion('off', $opinion_id) : $model->setOpinion('on', $opinion_id);
        //$opinions = $model->getAllOpinions();
        header('Location:index.php?controller=admin&task=showAllOpinions');
        exit;
        //\Renderer::render('adminOpinions', 'admin', compact('opinions'));
    }

    public function blockUser() {
        $users = $this->model->getallUsers();
        $email = $users[$_GET['id']-1]['email'];
        ($this->model->getUser($email)['blocked'] === 'false') ? $this->model->setUser('true', $email) : $this->model->setUser('false', $email);
        header('Location:index.php?controller=admin&task=showAllUsers');
        exit;
        //\Renderer::render('adminUsers', 'admin', compact('users'));
    }

    public function processContact() {
        $model = new \Models\Contact;
        $contact_id = $_GET['id'];
        ($model->getOneContact($contact_id)['processed'] === 'on') ? $model->setContact('off', $contact_id) : $model->setContact('on', $contact_id);
        header('Location:index.php?controller=admin&task=showAllContacts');
        exit;
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
        header('Location:index.php?controller=admin&task=showAllProducts');
        exit;
        //\Renderer::render('adminProducts', 'admin', compact('products'));
    }

    
}