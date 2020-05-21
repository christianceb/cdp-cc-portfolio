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

// Handle POSTed data from input stream into something useful
$handleRaw = handleRaw2JSON( file_get_contents('php://input') );

/**
 * Set flag if insert was successful. Failed by default
 * -1 = Incomplete
 * 0 = Failed
 * 1 = Success
 */
$inserted = 0;

// Run create routine when able
if ( $handleRaw['success'] ) {
  // If true, set flag to 1, 0 otherwise
  $inserted = $category->create( $handleRaw['body'] ) ? 1 : 0;
} else {
  // We flag $inserted as incomplete if JSON decode also failed
  $inserted = -1;
}

// Set respective HTTP response code and corresponding message;
if ( $inserted === -1 ) {
  $code = 400;
  $message = "Bad Request";
} else if ( $inserted === 0 ) {
  $code = 503;
  $message = "Service Unavailable";
} else {
  $code = 200;
  $message = "OK";
}

// Set HTTP Response Code
http_response_code( $code );

// Then some "meaningful" (error) message
echo json_encode( [ "message" => $message ] );