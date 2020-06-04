<?php
// Set API response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Set default fail states
$httpResponseCode = 404;
$jsonResponse = ["message" => "No rows found."];

// Prepare required classes and objects
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

// Instantiate PDO and retrieve persistent connection resource
$database = new Database();
$databaseConnection = $database->getConnection();

// Pass database connection resource to collection class
$category = new Category($databaseConnection);

// Get page if set and ensure it's zero-indexed
$page = isset($_GET['page']) ? floor($_GET['page']) : 0;
$page > 0 ? $page-- : 0;

// Explicitly set limit which can be used later to determine pagination parameters
$limit = 5;

// Retrieve entries being browsed
$statement = $category->read($page, $limit);

// Check if we have some row(s)
if ($statement->rowCount() > 0) {
  // Instantiate empty array for items in results
  $categories = array();

  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    // Instantiate pseudo-empty class
    $categoryRow = [];

    // Retrieve mapped table columns from categories
    foreach ($category->tableColumns as $column) {
      $categoryRow[$column] = $row[$column] ?? null;
    }

    // Push built row into list
    array_push($categories, $categoryRow);
  }

  // Set response code of successful request
  $httpResponseCode = 200;

  // Return results as part of response
  $jsonResponse = [
    'results' => $categories,
    'pages' => ceil($category->count() / $limit)
  ];
}

// Return necessary data
http_response_code($httpResponseCode);
echo json_encode($jsonResponse);
