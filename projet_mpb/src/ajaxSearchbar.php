<?php

require 'models/Product.php';
$content = file_get_contents("php://input"); 
// php://input is a read-only stream that allows reading raw data from the request body
$data = json_decode($content, true);
$search = "%" . $data['textToFind'] . "%";
$model = new \Models\Product();
$searchProducts = $model->getProductFromSearchbar($search);
// number of results 
$numberOfProducts = count($searchProducts); 

include '../views/searchProducts.phtml';