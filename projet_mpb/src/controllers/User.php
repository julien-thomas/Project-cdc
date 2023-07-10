<?php

namespace Controllers;

class User extends Controller
{

    protected $modelName = \Models\User::class;

    public function login()
    {


        // Traitement du form suite au Submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Empty
            if (!in_array('', $_POST)) {
                // Email
                if (array_key_exists('mail', $_POST) && filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                    // On continu le traitement
                    // Si inexistant, on continu
                    if ($user = $this->model->getUser($_POST['mail'])) {
                        // Check Pwd
                        if (password_verify($_POST['password'], $user['password'])) {
                            if ($user['blocked'] === 'false') {
                                //  Authentification (c'est ici que l'on devrait ajouter un token de sécurité => faille CSRF)
                                $_SESSION['user'] = [
                                    'id'        => $user['id'],
                                    'email'     => $_POST['mail'],
                                    'role'      => $user['roles_id'] == 1 ? 'user' : 'admin',
                                    'token'     => bin2hex(random_bytes(32))
                                ];
                                // Norification de succès
                                $_SESSION['success'] = 'Vous êtes bien connecté';
                                // Redirection vers home
                                //header('Location: index.php');
                                //exit;
                                \Redirection::redirect('index.php');
                                // \Renderer::render('home');
                            } else $_SESSION['error'] = 'Votre compte a été bloqué';
                        } else $_SESSION['error'] = 'Erreur : Mot de passe invalide';
                    } else $_SESSION['error'] = 'Erreur : Cet utilisateur n\'existe pas';
                } else $_SESSION['error'] = 'Erreur : Email invalide';
            } else $_SESSION['error'] = 'Erreur : Le formulaire doit être complètement rempli';
        }
        // Template default

        \Renderer::render('login', 'layout');
        
    }

    public function register()
    {

        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $newUser = [
                'mail'      => trim($_POST['mail']),
                'firstname' => trim($_POST['firstname']),
                'lastname'  => trim($_POST['lastname']),
                'birthday'  => trim($_POST['birthday']),
                'address'   => trim($_POST['address']),
                'zipCode'   => trim($_POST['zipCode']),
                'city'      => trim($_POST['city']),
                'country'   => trim($_POST['country']),
                'password'  => trim($_POST['password'])
            ];

            $errors = [];

            // var_dump($newUser);

            if (in_array('', $_POST))
                $errors[] = 'Veuillez remplir tous les champs';

            if (!filter_var($newUser['mail'], FILTER_VALIDATE_EMAIL) || count($newUser) > 160)
                $errors[] = 'Votre email est invalide';

            if (strlen($newUser['firstname']) < 3 || strlen($newUser['firstname']) > 100)
                $errors[] = 'Votre prénom doit comporter entre 2 et 100 caractères';

            if (strlen($newUser['lastname']) < 3 || strlen($newUser['lastname']) > 100)
                $errors[] = 'Votre nom doit comporter entre 2 et 100 caractères';

            $interval = date_diff(date_create(), date_create($newUser['birthday']));
            // var_dump($interval);
            if ($interval->y < 18)
                $errors[] = 'Vous devez avoir plus de 18 ans';
            // $interval->format("You are  %Y Year, %M Months, %d Days, %H Hours, %i Minutes, %s Seconds Old");

            if (strlen($newUser['address']) > 200)
                $errors[] = 'Votre addresse ne doit pas comporter plus de 200 caractères';

            if (strlen($newUser['zipCode']) > 15)
                $errors[] = 'Votre code postal ne doit pas comporter plus de 15 caractères';

            if (strlen($newUser['city']) > 50)
                $errors[] = 'Votre addresse ne doit pas comporter plus de 50 caractères';

            if (strlen($newUser['country']) > 200)
                $errors[] = 'Votre addresse ne doit pas comporter plus de 50 caractères';

            if ($this->model->getUser($newUser['mail']))
                $errors[] = 'Un utilisateur existe déjà avec cet email';
            var_dump($errors);
            if (count($errors) === 0) {
                $this->model->addUser($newUser);
                // Norification de succès
                $_SESSION['success'] = 'Vous êtes à présent enregistré';
                // Redirection vers login
                header('Location: index.php?controller=user&task=login');
                exit;
            } else $_SESSION['error'] = $errors[0];
            
                

            /*
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Empty
            if (!in_array('', $_POST)) {
                // Email
                if (array_key_exists('mail', $_POST) && filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) && count($_POST['mail']) <= 160 ) {
                    // Si inexistant, on continue 
                    if (!$this->model->findByEmail($_POST['mail'])) {
                        // Insert User
                        $this->model->insert($_POST);
                        // Norification de succès
                        $_SESSION['success'] = 'Vous êtes à présent enregistré';
                        // Redirection vers login
                        \Renderer::render('login');
                    } else $_SESSION['error'] = 'Un utilisateur existe déjà avec cet email';
                } else $_SESSION['error'] = 'Email invalide';
            } else $_SESSION['error'] = 'Le formulaire doit être complètement rempli';
        }
        */
        }
        // Template default
        \Renderer::render('register', 'layout');
    }

    public function opinion() {
        $model = new \Models\Opinion;
        if (isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $opinion = [
                    'score' => trim($_POST['score']),
                    'pseudo' => trim($_POST['pseudo']),
                    'title' => trim($_POST['title']),
                    'opinion' => trim($_POST['opinion'])
                ];

                $errors = [];

                if (in_array('', $_POST))
                    $errors[] = 'Veuillez remplir tous les champs';

                if (strlen($opinion['pseudo']) < 2 || strlen($opinion['pseudo']) > 20)
                    $errors[] = 'Votre pseudo doit comporter entre 2 et 20 caractères';

                if (strlen($opinion['title']) < 2 || strlen($opinion['pseudo']) > 20)
                    $errors[] = 'Votre avis en 2 mots doit comporter entre 2 et 20 caractères';

                $product_id = null;
                if (!empty($_POST['product_id']) && ctype_digit($_POST['product_id'])) {
                    $product_id = $_POST['product_id'];
                }

                if (count($errors) === 0) {
                    $model->addOpinion($opinion);
                    // Norification de succès
                    $_SESSION['success'] = 'Votre avis a bien été enregistré';
                    // Redirection vers 
                    //header('Location: index.php');
                    //exit;
                    /* header('Location:index.php?controller=product&task=showOne&id=' . $product_id);
                    exit; */
                    \Redirection::redirect('index.php?controller=product&task=showOne&id=' . $product_id);
                } else {
                    $_SESSION['error'] = $errors[0];
                    /* header('Location:index.php?controller=product&task=showOne&id=' . $product_id);
                    exit; */
                    \Redirection::redirect('index.php?controller=product&task=showOne&id=' . $product_id);
                }
            }  
            //\Renderer::render('productSheet', 'layout');
        }    
            else {
            $_SESSION['error'] = 'Veuillez vous connecter';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Redirection::redirect('index.php?controller=user&task=login');
            }
        
    }

    public function member()
    {
        if (isset($_SESSION['user'])) {
            $user = $this->model->getUser($_SESSION['user']['email']);
            \Renderer::render('member', 'layout', compact('user'));
        }  else {
            $_SESSION['error'] = 'Veuillez vous connecter';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    public function changePassword() {
        if (isset($_SESSION['user'])) {
            //$user = $this->model->getUser($_SESSION['user']['email']);
            //\Renderer::render('changePassword', 'layout', compact('user'));
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['token']) ||
                !hash_equals($_SESSION['user']['token'], $_POST['token'])) {
                http_response_code(400);
                exit('Request Forbidden');
                } else {
                    if (!in_array('', $_POST)) {
                        $user = $this->model->getUser($_SESSION['user']['email']);
                        if (password_verify($_POST['currentPassword'], $user['password'])) {
                            if ($_POST['newPassword'] === $_POST['newPasswordConfirm']) {
                                try {
                                $this->model->newPassword($_POST['newPassword']);
                                $_SESSION['success'] = 'Changement de mot de passe réussi';
                                /* header('Location: index.php?controller=user&task=member');
                                exit; */
                                \Redirection::redirect('index.php?controller=user&task=member');
                                } catch (\PDOException $e) {
                                $_SESSION['error'] = 'Une erreur est survenue lors de la tentative de mise à jour, merci de réessayer plus tard';
                                }
                            } else $_SESSION['error'] = 'Les mots de passe ne sont pas identiques';
                        } else $_SESSION['error'] = 'Le mot de passe est erroné';
                    } else $_SESSION['error'] = 'Veuillez remplir tous les champs';
                }
            }
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter';
            // Redirection vers login
            /* header('Location: index.php?controller=user&task=login');
            exit; */
            \Redirection::redirect('index.php?controller=user&task=login');
        }
        \Renderer::render('changePassword', 'layout');
    }

    public function admin()
    {
        \Renderer::render('adminProducts', 'admin');
    }

    public function logout()
    {
        // Déconnecter le user
        unset($_SESSION['user']);
        // Notification de succès
        $_SESSION['success'] = 'Vous êtes bien déconnecté';
        // Template default
        /* header('Location: index.php');
        exit; */
        \Redirection::redirect('index.php');
    }

    public function myOrders()
    {
        \Renderer::render('myOrders', 'layout');
    }
}
