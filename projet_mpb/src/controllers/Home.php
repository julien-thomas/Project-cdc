<?php

namespace Controllers;

class Home extends Controller {

    protected $modelName = \Models\Contact::class;

    public function showHomePage() {

        $model = new \Models\Product;
        $products = $model->getSelectedProducts();
        \Apps\Renderer::render('home', 'layout', compact('products'));
    }

    public function contact() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contact = [
                'subject' => trim($_POST['subject']),
                'mail' => trim($_POST['mail']),
                'content' => trim($_POST['content'])
            ];
        
            $errors = [];

            if (in_array('', $_POST))
                $errors[] = 'Veuillez remplir tous les champs';

            if (!filter_var($contact['mail'], FILTER_VALIDATE_EMAIL) || count($contact) > 160)
                $errors[] = 'Votre email est invalide';

            if (strlen($contact['content']) > 300)
                $errors[] = 'Votre message ne doit pas comporter plus de 300 caractères'; 
            var_dump($_POST);
        
            if (count($errors) === 0) {
                $this->model->contactUs($contact);
                // Norification de succès
                $_SESSION['success'] = 'Votre message a bien été envoyé';
                // Redirection vers login
                /* header('Location: index.php');
                exit; */
                \Apps\Redirection::redirect('index.php');
            } else $_SESSION['error'] = $errors[0];
    }
    \Apps\Renderer::render('contact', 'layout');
    }

    public function showMentions() {
        \Apps\Renderer::render('mentions', 'layout');
    }
}

// index.php -> controller --> model (liste les requetes) --> database (prepare et execute les requete)
// Database retourne le resultat au model --> le model retoune le résultat au controller
// Le controller donne les données à la vue ( la vue se content QUE d'afficher les données )
// Dès que l'utilisateur clique sur un lien --> index.php