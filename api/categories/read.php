<?php
// Set API response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Prepare required classes and objects
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

// Instantiate PDO and retrieve persistent connection resource
$database = new Database();
$databaseConnection = $database->getConnection();

// Pass database connection resource to collection class
$category = new Category( $databaseConnection );

// Retrieve entries being browsed
$statement = $category->read();

// check if more than 0 record found
if ( $statement->rowCount() > 0 ) {
  // Instantiate empty array for items in results
  $categories = array();

  while ( $row = $statement->fetch( PDO::FETCH_ASSOC ) ) {
    // Instantiate pseudo-empty class
    $categoryRow = [];

    // Retrieve mapped table columns from categories
    foreach ( $category->tableColumns as $column ) {
      $categoryRow[ $column ] = $row[ $column ] ?? null;
    }

    // Push built row into list
    array_push( $categories, $categoryRow );
  }

  // Set response code of successful request
  http_response_code(200);

  // Return list as a JSON format
  echo json_encode($categories);
} else {
  // Set 'not found' response code
  http_response_code(404);

  // Return error message in a JSON format
  echo json_encode( [ "message" => "No rows found." ] );
}