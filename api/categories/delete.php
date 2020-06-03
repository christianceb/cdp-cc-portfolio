<?php
// Set API response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Set default fail states
$httpResponseCode = 503;
$jsonResponse = ["message" => "Malformed"];

// Prepare required classes and objects
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

// Instantiate PDO and retrieve persistent connection resource
$database = new Database();
$databaseConnection = $database->getConnection();

// Pass database connection resource to collection class
$category = new Category($databaseConnection);

// Assume validation is successful unless...
$validateSuccess = true;

// Set necessary response if ID is not set
if (!isset($_GET['id'])) {
  $httpResponseCode = 404;
  $jsonResponse = ["message" => "No ID set"];

  $validateSuccess = false;
}

// Set necessary response if ID is invalid (0 included)
if ($validateSuccess && !filter_var($_GET['id'], FILTER_VALIDATE_INT, ['min_range' => 1])) {
  $httpResponseCode = 404;
  $jsonResponse = ["message" => "Invalid ID"];


  $validateSuccess = false;
}

if ($validateSuccess) {
  // Start referencing to ID NOT in $_GET
  $id = $_GET['id'];

  $result = $category->delete($id);

  if ($result) {
    $httpResponseCode = 200;
    $jsonResponse = ["message" => "Row deleted"];
  }
  // Otherwise, use default fail state (malformed)
}

// Return necessary data
http_response_code($httpResponseCode);
echo json_encode($jsonResponse);
