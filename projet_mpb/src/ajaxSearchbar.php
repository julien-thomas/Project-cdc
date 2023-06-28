<?php

require 'models/product.php';
$content = file_get_contents("php://input");
$data = json_decode($content, true);
$search = "%" . $data['textToFind'] . "%";
$model = new \Models\Product();
$searchProducts = $model->getProductFromSearchbar($search);

$numberOfProducts = count($searchProducts); // On en profite pour compter le nombre de résultats

include '../views/searchProducts.phtml';