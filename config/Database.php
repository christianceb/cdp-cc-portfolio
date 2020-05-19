<?php
/**
 * Sets up a database connection using PDO.
 */
class Database
{
  private $type = 'mysql';
  private $name = 'rad_store';
  private $host = 'localhost';
  private $port = '3306';
  private $charSet = 'utf8';
  private $user = 'christian.ponce';
  private $password = 'Secret1';
  private $dsn = '';
  private $connection;

  /**
   * Builds DSN based on properties set in the object
   */
  public function __construct()
  {
    $this->dsn = "{$this->type}:dbname={$this->name};" .
      "host={$this->host};port={$this->port};" .
      "charset={$this->charSet}";
  }

  /**
   * Create PDO based on properties set in the object
   * 
   * @return PDO
   */
  public function getConnection()
  {
    $this->connection = new PDO(
      $this->dsn,
      $this->user,
      $this->password
    );

    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    return $this->connection;
  }
}