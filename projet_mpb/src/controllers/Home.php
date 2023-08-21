<?php

namespace Controllers;

class Home extends Controller {

    protected $modelName = \Models\Contact::class;

    /**
     * Displays the home page
     * 
     * @return void
     */
    public function showHomePage(): void {

        $model = new \Models\Product;
        $products = $model->getSelectedProducts();
        // compact(): creation of an array with the variables that we need
        \Apps\Renderer::render('home', 'layout', compact('products'));
    }

    /**
     * Displays and processes the contact form
     * 
     * @return null|array
     */
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
                $_SESSION['success'] = 'Votre message a bien été envoyé';
                \Apps\Redirection::redirect('index.php');
            } else $_SESSION['error'] = $errors[0];
    }
    \Apps\Renderer::render('contact', 'layout');
    }

    /**
     * Displays the page of legal notices
     * 
     * @return void
     */
    public function showMentions() {
        \Apps\Renderer::render('mentions', 'layout');
    }
}
