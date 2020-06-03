<?php
include_once '../../functions/helpers.php';

/**
 * Product class to provide BREAD capabilities to its records
 */
class Product
{
  private $connection;

  /**
   * Target table name
   *
   * @var string
   */
  private $tableName = "products";

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
    "name",
    "description",
    "price",
    "image",
    "category_id",
    "created_at",
    "updated_at",
    "deleted_at"
  ];

  /**
   * Constructor to keep reference of persistent database connection into class
   *
   * @param PDO $databaseConnection
   */
  public function __construct($databaseConnection)
  {
    $this->connection = $databaseConnection;
  }

  /**
   * Browse for records
   * 
   * @return PDOStatement Result of the executed statement
   */
  public function read()
  {
    $query =
      "SELECT * " .
      "FROM `{$this->tableName}` " .
      "ORDER BY `{$this->defaultOrderBy}` {$this->defaultOrder} ";

    // Retrieve PDO Statement object based off query string
    $statement = $this->connection->prepare($query);

    // Execute PDO Statement
    $statement->execute();

    return $statement;
  }

  
  /**
   * Browse for products given a category ID
   * 
   * @param int $id Category ID to search for products
   * @return PDOStatement Result of the executed statement
   */
  public function category($categoryId)
  {
    $query =
      "SELECT * " .
      "FROM `{$this->tableName}` " .
      "WHERE `category_id` = :id " . 
      "ORDER BY `{$this->defaultOrderBy}` {$this->defaultOrder} ";

    // Retrieve PDO Statement object based off query string
    $statement = $this->connection->prepare($query);

    // (needlessly) sanitize just to be sure
    $categoryId = s6eInt($categoryId);

    // Bind id being searched to id placeholder in query
    $statement->bindParam(":id", $categoryId);

    // Execute PDO Statement
    $statement->execute();

    return $statement;
  }
}
