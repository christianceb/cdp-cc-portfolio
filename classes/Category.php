<?php
class Category
{
  private $connection;
  private $tableName = "categories";

  public $id;
  public $code;
  public $title;
  public $description;
  public $icon;
  public $createdAt;
  public $updatedAt;
  public $deletedAt;

  public function __constructor( $databaseConnection ) {
    $this->connection = $databaseConnection;
  }
}