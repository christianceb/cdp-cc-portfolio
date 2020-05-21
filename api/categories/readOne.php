<?php
// Set API response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Set default fail states
$http_response_code = 404;
$json_response = [ "message" => "No rows found." ];

// Prepare required classes and objects
include_once '../../config/Database.php';
include_once '../../classes/Category.php';


// Instantiate PDO and retrieve persistent connection resource
$database = new Database();
$databaseConnection = $database->getConnection();

// Pass database connection resource to collection class
$category = new Category( $databaseConnection );

// TODO: clarify and implement how value of $id should be retrieve (php://input is a no-go)
$id = isset( $_GET['id'] ) ? floor( $_GET['id'] ) : null; // Floor to ensure its a number and not a decimal

// Ensure we don't trick ourselves to null
if ( $id !== null ) {
  // Retrieve entries being browsed
  $statement = $category->readOne( $id );

  // Check if we have some row(s)
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

      // Break after first iteration. We're only retrieving 1 row, right?
      break;
    }

    // Set response code of successful request
    $http_response_code = 200;

    // Return results as part of response
    $json_response = $categories;
  }
}

// Return necessary data
http_response_code( $http_response_code );
echo json_encode( $json_response );