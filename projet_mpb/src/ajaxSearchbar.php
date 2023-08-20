<?php

require 'models/Product.php';
$content = file_get_contents("php://input"); 
//php://input est un flux en lecture seule qui permet de lire des données brutes depuis le corps de la requête
$data = json_decode($content, true);
$search = "%" . $data['textToFind'] . "%";
$model = new \Models\Product();
$searchProducts = $model->getProductFromSearchbar($search);

$numberOfProducts = count($searchProducts); // On en profite pour compter le nombre de résultats

include '../views/searchProducts.phtml';