<?php

namespace Controllers;

class User extends Controller
{

    protected $modelName = \Models\User::class;

    /**
     * login function
     * 
     * @return void
     */
    public function login(): void
    {
        // form processing after submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // check if not empty
            if (!in_array('', $_POST)) {
                // check email validity
                if (array_key_exists('mail', $_POST) && filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                    // check if user exist
                    if ($user = $this->model->getUser($_POST['mail'])) {
                        // check password
                        if (password_verify($_POST['password'], $user['password'])) {
                            // check if user's blocked
                            if ($user['blocked'] === 'false') {
                                //  Authentication and token creation (against CSRF attacks)
                                $_SESSION['user'] = [
                                    'id'        => $user['id'],
                                    'email'     => $_POST['mail'],
                                    'role'      => $user['roles_id'] == 1 ? 'user' : 'admin',
                                    'token'     => bin2hex(random_bytes(32))
                                ];
                                // success notification
                                $_SESSION['success'] = 'Vous êtes bien connecté';
                                // Redirection
                                \Apps\Redirection::redirect('index.php');
                            } else $_SESSION['error'] = 'Votre compte a été bloqué';
                        } else $_SESSION['error'] = 'Erreur : Mot de passe invalide';
                    } else $_SESSION['error'] = 'Erreur : Cet utilisateur n\'existe pas';
                } else $_SESSION['error'] = 'Erreur : Email invalide';
            } else $_SESSION['error'] = 'Erreur : Le formulaire doit être complètement rempli';
        }
        // Default template
        \Apps\Renderer::render('login', 'layout');
    }

    /**
     * register function
     * 
     * @return void
     */
    public function register(): void
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

            if (in_array('', $_POST))
                $errors[] = 'Veuillez remplir tous les champs';

            if (!filter_var($newUser['mail'], FILTER_VALIDATE_EMAIL) || strlen($newUser['mail']) > 160)
                $errors[] = 'Votre email est invalide';

            if (strlen($newUser['firstname']) < 2 || strlen($newUser['firstname']) > 100)
                $errors[] = 'Votre prénom doit comporter entre 2 et 100 caractères';
            if (!preg_match('/^[A-Za-zÀ-ÿ,.\'\s]+$/', $newUser['firstname']))
                $errors[] = 'Votre prénom ne doit pas comporter de chiffres ou de caractères spéciaux';

            if (strlen($newUser['lastname']) < 2 || strlen($newUser['lastname']) > 100)
                $errors[] = 'Votre nom doit comporter entre 2 et 100 caractères';
            if (!preg_match('/^[A-Za-zÀ-ÿ,.\'\s]+$/', $newUser['lastname']))
                $errors[] = 'Votre nom ne doit pas comporter de chiffres ou de caractères spéciaux';

            $interval = date_diff(date_create(), date_create($newUser['birthday']));
            if ($interval->y < 18 || $interval->y > 120)
                $errors[] = 'Vous devez avoir plus de 18 ans et être encore en vie...';
            // $interval->format("You are  %Y Year, %M Months, %d Days, %H Hours, %i Minutes, %s Seconds Old");

            if (strlen($newUser['address']) < 2 || strlen($newUser['address']) > 200)
                $errors[] = 'Votre addresse ne doit pas comporter plus de 200 caractères';

            if (strlen($newUser['zipCode']) < 2 || strlen($newUser['zipCode']) > 15)
                $errors[] = 'Votre code postal ne doit pas comporter plus de 15 caractères';

            if (strlen($newUser['city']) < 2 || strlen($newUser['city']) > 50)
                $errors[] = 'Votre ville ne doit pas comporter plus de 50 caractères';
            if (!preg_match('/^[A-Za-zÀ-ÿ,.\'\s]+$/', $newUser['city']))
                $errors[] = 'Votre ville ne doit pas comporter de chiffres ou de caractères spéciaux';

            if (strlen($newUser['country']) < 2 || strlen($newUser['country']) > 50)
                $errors[] = 'Votre pays ne doit pas comporter plus de 50 caractères';
            if (!preg_match('/^[A-Za-zÀ-ÿ,.\'\s]+$/', $newUser['country']))
                $errors[] = 'Votre pays ne doit pas comporter de chiffres ou de caractères spéciaux';

            if (strlen($newUser['password']) < 8 || strlen($newUser['password']) > 100)
                $errors[] = 'Votre mot de passe doit comporter entre 8 et 100 caractères';

            if ($this->model->getUser($newUser['mail']))
                $errors[] = 'Un utilisateur existe déjà avec cet email';

            if (count($errors) === 0) {
                $this->model->addUser($newUser);
                $_SESSION['success'] = 'Vous êtes à présent enregistré';
                \Apps\Redirection::redirect('index.php?controller=user&task=login');
            } else $_SESSION['error'] = $errors[0];
        }
        // Template default
        \Apps\Renderer::render('register', 'layout');
    }

    /**
     * adds a review
     * 
     * @return void
     */
    public function opinion(): void
    {
        $model = new \Models\Opinion;
        if (isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (
                    !isset($_POST['token']) ||
                    !hash_equals($_SESSION['user']['token'], $_POST['token'])
                ) {
                    http_response_code(400);
                    exit('Request Forbidden');
                } else {

                    $opinion = [
                        'score'     => $_POST['score'],
                        'pseudo'    => trim($_POST['pseudo']),
                        'title'     => trim($_POST['title']),
                        'opinion'   => trim($_POST['opinion'])
                    ];

                    $errors = [];

                    if (in_array('', $_POST))
                        $errors[] = 'Veuillez remplir tous les champs';

                    if (strlen($opinion['pseudo']) < 2 || strlen($opinion['pseudo']) > 20)
                        $errors[] = 'Votre pseudo doit comporter entre 2 et 20 caractères';

                    if (strlen($opinion['title']) < 2 || strlen($opinion['title']) > 20)
                        $errors[] = 'Votre avis en 2 mots doit comporter entre 2 et 20 caractères';

                    if (strlen($opinion['opinion']) < 2 || strlen($opinion['opinion']) > 1000)
                        $errors[] = 'Votre avis doit comporter entre 2 et 1000 caractères';

                    $product_id = null;
                    if (!empty($_POST['product_id']) && ctype_digit($_POST['product_id'])) {
                        $product_id = $_POST['product_id'];
                    }

                    if (count($errors) === 0) {
                        $model->addOpinion($opinion);
                        $_SESSION['success'] = 'Votre avis a bien été enregistré';
                        \Apps\Redirection::redirect('index.php?controller=product&task=showOne&id=' . $product_id);
                    } else {
                        $_SESSION['error'] = $errors[0];
                        \Apps\Redirection::redirect('index.php?controller=product&task=showOne&id=' . $product_id);
                    }
                }
            }
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * display user account
     * 
     * @return void
     */
    public function member(): void
    {
        if (isset($_SESSION['user'])) {
            $user = $this->model->getUser($_SESSION['user']['email']);
            \Apps\Renderer::render('member', 'layout', compact('user'));
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
    }

    /**
     * displays the change password page and processes the user password change
     * 
     * @return void
     */
    public function changePassword(): void
    {
        if (isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (
                    !isset($_POST['token']) ||
                    !hash_equals($_SESSION['user']['token'], $_POST['token'])
                ) {
                    http_response_code(400);
                    exit('Request Forbidden');
                } else {
                    if (!in_array('', $_POST)) {
                        $user = $this->model->getUser($_SESSION['user']['email']);
                        if (password_verify($_POST['currentPassword'], $user['password'])) {
                            if (
                                strlen($_POST['newPassword']) > 7 && strlen($_POST['newPasswordConfirm']) > 7
                                && strlen($_POST['newPassword']) < 101 && strlen($_POST['newPasswordConfirm']) < 101
                            ) {
                                if ($_POST['newPassword'] === $_POST['newPasswordConfirm']) {
                                    try {
                                        $this->model->newPassword($_POST['newPassword']);
                                        $_SESSION['success'] = 'Changement de mot de passe réussi';
                                        \Apps\Redirection::redirect('index.php?controller=user&task=member');
                                    } catch (\PDOException $e) {
                                        $_SESSION['error'] = 'Une erreur est survenue lors de la tentative de mise à jour, merci de réessayer plus tard';
                                    }
                                } else $_SESSION['error'] = 'Les mots de passe ne sont pas identiques';
                            } else $_SESSION['error'] = 'Votre mot de passe doit comporter entre 8 et 100 caractères';
                        } else $_SESSION['error'] = 'Le mot de passe est erroné';
                    } else $_SESSION['error'] = 'Veuillez remplir tous les champs';
                }
            }
        } else {
            $_SESSION['error'] = 'Veuillez vous connecter';
            \Apps\Redirection::redirect('index.php?controller=user&task=login');
        }
        \Apps\Renderer::render('changePassword', 'layout');
    }

    /**
     * disconnect the user
     * 
     * @return void
     */
    public function logout(): void
    {
        // Déconnecter le user
        unset($_SESSION['user']);
        $_SESSION['success'] = 'Vous êtes bien déconnecté';
        \Apps\Redirection::redirect('index.php');
    }

    /* public function myOrders()
    {
        \Apps\Renderer::render('myOrders', 'layout');
    } */

    /* public function admin()
    {
        \Apps\Renderer::render('adminProducts', 'admin');
    } */
}
