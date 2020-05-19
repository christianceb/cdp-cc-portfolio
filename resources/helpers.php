<?php
/**
 * Helper function to handle string (implied php://input) that is expected to be JSON and parse it
 *
 * @param string $raw
 * @return array result of the handling of the input
 */
function handleRaw2JSON( $raw )
{
  $result = [
    "success" => false,
    "error" => "",
    "body" => ""
  ];

  // Sane JSON check
  // https://stackoverflow.com/questions/6041741/fastest-way-to-check-if-a-string-is-json-in-ph
  $decoded = json_decode( $raw, true );

  // Only set success flags and decode body if there were no errors
  if ( json_last_error() === JSON_ERROR_NONE ) {
    $result['success'] = true;
    $result['body'] = $decoded;
  } else {
    // Return last error message
    $result['error'] = json_last_error_msg();
  }

  return $result;
}

/**
 * Given a list of keys, loop through it and ensure that body has that key. Otherwise, return false
 *
 * @param array $requiredColumns List of keys and values you require on $body
 * @param array $body Array to be inspected for an instance of the required columns
 * @return bool True if requirements are met, false otherwise.
 */
function checkRequired( $requiredColumns, $body )
{
  foreach ( $requiredColumns as $column ) {
    // If key does not exist, no point in continuing
    if ( ! array_key_exists( $column, $body ) ) {
      return false;
    } else if ( $body[ $column ] == "" ) {
      // If the key exists but the value is blank, then the requirement wasn't satisfied
      return false;
    }
  }

  return true;
}

/**
 * Shorthand for s(anitis)e_str(ing) because this function will be abused A LOT!
 *
 * @param string $value string to be made safe for storing into a database
 * @return string
 */
function s6eStr( $value ) {
  return filter_var( trim( htmlspecialchars( $value ) ) , FILTER_SANITIZE_STRING );
}