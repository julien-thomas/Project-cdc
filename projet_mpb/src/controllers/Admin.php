<?php

namespace Controllers;

class Admin extends Controller
{

    protected $modelName = \Models\User::class;

    /**
     * Displays all the products from the database in the dashboard
     * 
     * @return void
     */
    public function showAllProducts(): void {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $model = new \Models\Product;
            $products = $model->getAllProducts();
            \Apps\Renderer::render('adminProducts', 'admin', compact('products'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * Displays all the users from the database in the dashboard
     * 
     * @return void
     */
    public function showAllUsers(): void {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $users = $this->model->getAllUsers();
            \Apps\Renderer::render('adminUsers', 'admin', compact('users'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * Displays all the opionions from the database in the dashboard
     * 
     * @return void
     */
    public function showAllOpinions(): void {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $model = new \Models\Opinion;
            $opinions = $model->getAllOpinions();
            \Apps\Renderer::render('adminOpinions', 'admin', compact('opinions'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * Displays all the contacts from the database in the dashboard
     * 
     * @return void
     */
    public function showAllContacts(): void {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $model = new \Models\Contact;
            $contacts = $model->getAllContacts();
            \Apps\Renderer::render('adminContacts', 'admin', compact('contacts'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * Displays all the orders from the database in the dashboard
     * 
     * @return void
     */
    public function showAllOrders(): void {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $model = new \Models\Order;
            $orders = $model->getAllOrders();
            \Apps\Renderer::render('adminOrders', 'admin', compact('orders'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter en tant qu\'admin';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }    
    }

    /**
     * Select the products to display in the home page (if still in stock)
     * 
     * @return void
     */
    public function selectProduct(): void {
        $model = new \Models\Product;
        $product_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $product_id = $_GET['id'];
        }
        if (!$product_id || !$model->getOneProduct($product_id)) {
            $_SESSION['error'] = 'Ce produit n\'existe pas';
            \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
        } else {
            ($model->getOneProduct($product_id)['selected'] === 1) ? $model->setProduct(0, $product_id) : $model->setProduct(1, $product_id);
            \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
        }
    }

    /**
     * Blocks or unblocks the display of a review
     * 
     * @return void
     */
    public function blockOpinion(): void {
        $model = new \Models\Opinion;
        $opinion_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $opinion_id = $_GET['id'];
        }
        if (!$opinion_id || !$model->getOneOpinion($opinion_id)) {
            $_SESSION['error'] = 'Cet avis n\'existe pas';
            \Apps\Redirection::redirect('index.php?controller=admin&task=showAllOpinions');
        } else {
        ($model->getOneOpinion($opinion_id)['status'] === 'on') ? $model->setOpinion('off', $opinion_id) : $model->setOpinion('on', $opinion_id);
        \Apps\Redirection::redirect('index.php?controller=admin&task=showAllOpinions');
        }
    }

    /**
     * Blocks or unblocks a user's connection
     * 
     * @return void
     */
    public function blockUser() {
        $user_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $user_id = $_GET['id'];
        }
        if (!$user_id || !$this->model->getUserbyId($user_id)) {
            $_SESSION['error'] = 'Cet utilisateur n\'existe pas';
            \Apps\Redirection::redirect('index.php?controller=admin&task=showAllUsers');
        } else {
        ($this->model->getUserbyId($user_id)['blocked'] === 'false') ? $this->model->setUser('true', $user_id) : $this->model->setUser('false', $user_id);
        \Apps\Redirection::redirect('index.php?controller=admin&task=showAllUsers');
        }
    }

    /**
     * Modifies the status of a contactform
     * 
     * @return void
     */
    public function processContact() {
        $model = new \Models\Contact;
        $contact_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $contact_id = $_GET['id'];
        }
        if (!$contact_id || !$model->getOneContact($contact_id)) {
            $_SESSION['error'] = 'Ce contact n\'existe pas';
            \Apps\Redirection::redirect('index.php?controller=admin&task=showAllContacts');
        } else {
        ($model->getOneContact($contact_id)['processed'] === 'on') ? $model->setContact('off', $contact_id) : $model->setContact('on', $contact_id);
        \Apps\Redirection::redirect('index.php?controller=admin&task=showAllContacts');
        }
    }

    /**
     * Delete a product (if never ordered before)
     * 
     * @return void
     */
    public function deleteProduct() {
        $model = new \Models\Product;
        $product_id = trim($_POST['product_id']);
        if (!$model->getProductFromOrders($product_id))
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['token']) ||
                    !hash_equals($_SESSION['user']['token'], $_POST['token'])) {
                    http_response_code(400);
                    exit('Request Forbidden');
                } else {
                    $model->deleteProductById($product_id);
                }
            }
            $_SESSION['success'] = 'produit supprimé';
        } else {
            $_SESSION['error'] = 'Ce produit ne peut plus être supprimé';
        } \Apps\Redirection::redirect('index.php?controller=admin&task=showAllProducts');
    }

    /**
     * Delete a contactform
     * 
     * @return void
     */
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
        \Apps\Redirection::redirect('index.php?controller=admin&task=showAllContacts');
    }

    /**
     * Delete a review
     * 
     * @return void
     */
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
        \Apps\Redirection::redirect('index.php?controller=admin&task=showAllOpinions');
    }
    
}