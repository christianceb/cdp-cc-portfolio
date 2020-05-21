<?php
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

  public function create( $body ) {
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

    // Check if $body has all required columns for a new category
    if ( checkRequired( $requiredColumns, $body ) === false ) {
      return -1;
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
      $sanitizedRow[ $column ] = isset( $body[ $column ] ) ? s6eStr( $body[ $column ] ) : "";
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
}