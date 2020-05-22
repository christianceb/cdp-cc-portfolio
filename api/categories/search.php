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

// Handle POSTed data from input stream into something useful
$handleRaw = handleRaw2JSON( file_get_contents('php://input') );

// Similar to Google and Twitter, searches uses the `q` key except they use GET and not POST
if ( $handleRaw['success'] && isset( $handleRaw['body']['q'] ) ) {
  $statement = $category->search( $handleRaw['body']['q'] );

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