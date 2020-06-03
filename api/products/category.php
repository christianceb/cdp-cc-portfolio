<?php
// Set API response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Set default fail states
$httpResponseCode = 404;
$jsonResponse = ["message" => "No rows found."];

// Prepare required classes and objects
include_once '../../config/Database.php';
include_once '../../classes/Product.php';

// Instantiate PDO and retrieve persistent connection resource
$database = new Database();
$databaseConnection = $database->getConnection();

// Pass database connection resource to collection class
$product = new Product($databaseConnection);

$id = isset($_GET['id']) ? floor($_GET['id']) : null; // Floor to ensure its a number and not a decimal

// Ensure we don't trick ourselves to null. Sanity check $id too.
if ($id !== null && $id > 0) {
  // Retrieve entries being browsed
  $statement = $product->category($id);

  // Check if we have some row(s)
  if ($statement->rowCount() > 0) {
    // Instantiate empty array for items in results
    $products = array();

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      // Instantiate pseudo-empty class
      $productRow = [];

      // Retrieve mapped table columns from products
      foreach ($product->tableColumns as $column) {
        $productRow[$column] = $row[$column] ?? null;
      }

      // Push built row into list
      array_push($products, $productRow);
    }

    // Set response code of successful request
    $httpResponseCode = 200;

    // Return results as part of response
    $jsonResponse = $products;
  }
}

// Return necessary data
http_response_code($httpResponseCode);
echo json_encode($jsonResponse);
