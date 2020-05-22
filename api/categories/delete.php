<?php
// Set API response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Set default fail states
$http_response_code = 503;
$json_response = ["message" => "Malformed"];

// Prepare required classes and objects
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

// Instantiate PDO and retrieve persistent connection resource
$database = new Database();
$databaseConnection = $database->getConnection();

// Pass database connection resource to collection class
$category = new Category($databaseConnection);

// Assume validation is successful unless...
$validate_success = true;

// Set necessary response if ID is not set
if (!isset($_GET['id'])) {
  $http_response_code = 404;
  $json_response = ["message" => "No ID set"];

  $validate_success = false;
}

// Set necessary response if ID is invalid (0 included)
if ($validate_success && !filter_var($_GET['id'], FILTER_VALIDATE_INT, ['min_range' => 1])) {
  $http_response_code = 404;
  $json_response = ["message" => "Invalid ID"];


  $validate_success = false;
}

if ($validate_success) {
  // Start referencing to ID NOT in $_GET
  $id = $_GET['id'];

  $result = $category->delete($id);

  if ($result) {
    $http_response_code = 200;
    $json_response = ["message" => "Row deleted"];
  }
  // Otherwise, use default fail state (malformed)
}

// Return necessary data
http_response_code($http_response_code);
echo json_encode($json_response);
