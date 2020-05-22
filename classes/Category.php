<?php
include_once '../../functions/helpers.php';

class Category
{
  private $connection;

  /**
   * Target table name
   *
   * @var string
   */
  private $tableName = "categories";

  /**
   * Default column to sort
   *
   * @var string
   */
  private $defaultOrderBy = "created_at";

  /**
   * Default sort order
   *
   * @var string
   */
  private $defaultOrder = "DESC";
  
  /**
   * Map columns from table to recognize
   *
   * @var array
   */
  public $tableColumns = [
    "id",
    "code",
    "name",
    "description",
    "created_at",
    "updated_at",
    "deleted_at"
  ];

  public $id;
  public $code;
  public $title;
  public $description;
  public $createdAt;
  public $updatedAt;

  /**
   * Constructor to keep reference of persistent database connection into class
   *
   * @param PDO $databaseConnection
   */
  public function __construct( $databaseConnection ) {
    $this->connection = $databaseConnection;
  }

  /**
   * Browse for records
   *
   * @return void
   */
  public function read() {
    $query =
      "SELECT * " .
      "FROM `{$this->tableName}` " . 
      "ORDER BY `{$this->defaultOrderBy}` {$this->defaultOrder}";

    // Retrieve PDO Statement object based off query string
    $statement = $this->connection->prepare($query);

    // Execute PDO Statement
    $statement->execute();

    return $statement;
  }

  /**
   * Browse for a record using an ID
   *
   * @return void
   */
  public function readOne( $id ) {
    $query =
      "SELECT * " .
      "FROM `{$this->tableName}` " . 
      "WHERE `id` = :id";

    // Retrieve PDO Statement object based off query string
    $statement = $this->connection->prepare($query);

    // (needlessly) sanitize just to be sure
    $id = s6eStr( $id );

    // Bind id being searched to id placeholder in query
    $statement->bindParam( ":id", $id );

    // Execute PDO Statement
    $statement->execute();

    return $statement;
  }

  /**
   * Create a category
   *
   * @param array $category The category to be created
   * @return mixed -1 if provided data is incomplete, true if success, false if failed
   */
  public function create( $category ) {
    // Set required columns
    $requiredColumns = [
      'code',
      'description',
    ];

    // Set columns to disallow setting
    $disallowColumns = [
      'id',
      'created_at',
      'updated_at',
      'deleted_at',
    ];

    // Check if $category has all required columns for a new category
    if ( checkRequired( $requiredColumns, $category ) === false ) {
      return -1; // Does not have all required fields
    }

    // Build empty sanitized dictionary
    $sanitizedRow = [];

    // No need to be very paranoid from here on. Just sanitize.
    foreach ( $this->tableColumns as $column ) {
      // Skip columns that we disallow mutation
      if ( in_array( $column, $disallowColumns )  ) {
        continue;
      }

      // Add key/value pair to dictionary. Sanitize as needed
      $sanitizedRow[ $column ] = isset( $category[ $column ] ) ? s6eStr( $category[ $column ] ) : "";
    }

    // Build query from here onwards
    // Implodes keys in sanitizedRow to `look`, `like`, `this`
    $columnNames = "`" . implode( "`, `", array_keys( $sanitizedRow ) ) . "`";

    // And this for bind params to :look, :like, :this
    $params = ":" . implode( ", :", array_keys( $sanitizedRow ) );

    // Combine params and fragments into a query
    $query =
      "INSERT INTO `{$this->tableName}` ({$columnNames}) " .
      "VALUES ({$params})";

    // Retrieve PDO Statement object based off query string
    $statement = $this->connection->prepare($query);

    // The final loop to bind params. Pass by reference because $value mutates to last item in array!
    foreach ( $sanitizedRow as $key => &$value ) {
      $statement->bindParam( ":{$key}", $value );
    }

    return $statement->execute();
  }

  /**
   * Search for a record with some keywords
   *
   * @return void
   */
  public function search( $keywords ) {
    $query =
      "SELECT * " .
      "FROM `{$this->tableName}` " . 
      "WHERE `code` LIKE :keywords0 " . 
      "OR `description` LIKE :keywords1 " . 
      "ORDER BY `{$this->defaultOrderBy}` {$this->defaultOrder}";

    // Retrieve PDO Statement object based off query string
    $statement = $this->connection->prepare($query);

    // Sanitize keywords and prepare it for binding
    $keywords = "%" . s6eStr( $keywords ) . "%";

    // Bind id being searched to id placeholder in query
    $statement->bindParam( ":keywords0", $keywords );
    $statement->bindParam( ":keywords1", $keywords );

    // Execute PDO Statement
    $statement->execute();

    return $statement;
  }

  /**
   * Update a record
   *
   * @param array $category The category to be created
   * @return mixed -1 if provided data is incomplete, true if success, false if failed
   */
  public function update( $category ) {
    // Set required columns
    $requiredColumns = [
      'id',
      'code',
      'description',
    ];

    // Set columns to disallow updating
    $disallowColumns = [
      'created_at',
      'updated_at',
      'deleted_at',
    ];

    // Check if $category has all required columns for a new category
    if ( checkRequired( $requiredColumns, $category ) === false ) {
      return -1; // Does not have all required fields
    }

    // Build empty sanitized dictionary
    $sanitizedRow = [];

    // Build SET clause directives
    $setClause = [];

    // No need to be very paranoid from here on. Just sanitize.
    foreach ( $this->tableColumns as $column ) {
      // Skip columns that we disallow mutation
      if ( in_array( $column, $disallowColumns )  ) {
        continue;
      }

      // Add key/value pair to dictionary. Sanitize as needed
      $sanitizedRow[ $column ] = isset( $category[ $column ] ) ? s6eStr( $category[ $column ] ) : "";

      // We don't update the ID, we'll only reference to it.
      if ( $column !== "id" ) {
        $setClause[] = " {$column}=:{$column}";
        //$setClause .= " {$column}=:{$column}";
      }
    }

    // Combine params and fragments into a query
    $query =
      "UPDATE `{$this->tableName}` ".
      "SET " . implode( ', ', $setClause ) . " " .
      "WHERE `id`=:id";

    // Retrieve PDO Statement object based off query string
    $statement = $this->connection->prepare($query);

    // The final loop to bind params. Pass by reference because $value mutates to last item in array!
    foreach ( $sanitizedRow as $key => &$value ) {
      $statement->bindParam( ":{$key}", $value );
    }

    return $statement->execute();
  }
}