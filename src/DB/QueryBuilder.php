<?php

declare(strict_types=1);

namespace Matheus\TestePleno\DB;

use Exception;
use PDO;

class QueryBuilder
{
    public function __construct(
        private readonly object $connection,
        private readonly string $table,
    )
    {}

    public function find (
        ?array $terms = null,
        ?array $params = null,
        string $columns = "*",
        int $offset = 0,
        int $limit = 10,
    ): array
    {
        $query = "";
        $and = " and ";
        $terms_query = "";
        $types = [];

        $index = 1;
        if($params == null) {
            $params = $terms;
            foreach ($params as $key => $value) {
                (($index) >= count($params)) ? $and = '' : false;
                $terms_query = $terms_query . "{$key} = :{$key}{$and} ";

                (gettype($value) == 'string') ? $types[] = PDO::PARAM_STR :  $types[] = PDO::PARAM_INT;

                $index++;
            }
        }

        if(count($params) == 2 && isset($params['offset']) && isset($params['limit']))
            $this->fetch(offset: (int) $offset, limit: (int) $limit);
        
        $query = "SELECT {$columns} FROM {$this->table} WHERE {$terms_query} LIMIT :offset, :limit";

        $stmt = $this->connection->prepare($query);

        $params['offset'] = $offset;
        $params['limit'] = $limit;

        $types[] = PDO::PARAM_INT;
        $types[] = PDO::PARAM_INT;

        $index = 0;
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value, $types[$index]);
            $index++;
        }

        $stmt->execute();

        if($stmt->rowCount() === 1)
            return $stmt->fetch($this->connection::FETCH_ASSOC);
        
        return $stmt->fetchAll($this->connection::FETCH_ASSOC);
    }

    public function findById(int $id): array
    {   
      return $this->find(terms: ['id' => $id], offset: 0, limit: 1);
    }

    public function fetch(int $offset = 0, int $limit = 10): array
    {   
        try {

            $query = "SELECT * FROM {$this->table} LIMIT :offset, :limit";

            $stmt = $this->connection->prepare($query);

            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

            $stmt->execute();
    
            if($stmt->rowCount() > 0)
                return $stmt->fetchAll($this->connection::FETCH_ASSOC);
    
        } catch (Exception $exception) {
            return $exception;
        }
        
        return [];
    }

    public function create(array $data): array
    {
      try {
  
          $columns = implode(", ", array_keys($data));
          $values = ":" . implode(", :", array_keys($data));
          $stmt = $this->connection->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$values})");
          $stmt->execute($data);

          return $this->findById((int) $this->connection->lastInsertId());
      } catch (\PDOException $exception) {
          return $exception;
      }
    }

    public function update(array $data): array
    {
        try {
            $columns = "";
            $comma = ",";

            $index = 1;
            foreach ($data as $key => $value) {
                (($index) >= count($data)) ? $comma = '' : false;
                ($key != 'id') ? $columns = $columns . "{$key} = :{$key}{$comma} " : false;
                $index++;
            }

            $stmt = $this->connection->prepare("UPDATE {$this->table} SET {$columns} WHERE id = :id");

            $stmt->execute($data);

            return $this->findById((int) $data['id']);
        } catch (\PDOException $exception) {

            return $exception;
        }
    }

    public function delete(string $terms, ?array $params): bool
    {
        try {

            $query = "DELETE FROM {$this->table} WHERE {$terms}";
            $stmt = $this->connection->prepare($query);

            $stmt->execute($params);

            if($stmt->rowCount() > 0)
                return true;

        } catch (Exception $exception) {
            
            return $exception;
        }
    }
}