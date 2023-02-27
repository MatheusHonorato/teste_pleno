<?php

declare(strict_types=1);

namespace Matheus\TestePleno\DB;

use Exception;

class QueryBuilder
{
    const FIRST = 0;

    public function __construct(
        private readonly object $connection,
        private readonly string $table,
    )
    {}

    public function find (
        ?array $terms = null,
        ?array $params = null,
        string $columns = "*",
    ): array
    {

        $query = "";
        $and = " or ";
        $terms_query = "";
        $flag_like = false;

        $index = 1;
        if($params == null) {
            $params = $terms;
            foreach ($params as $key => $value) {

                (($index) >= count($params)) ? $and = '' : false;

                (($index) >= count($params)) ? $or = '' : false;

                if(is_array($value))
                {
                    $terms[$key] = implode(", ", $value);
                    $terms_query = $terms_query . "{$key} IN ({$terms[$key]}){$and} ";
                    unset($terms[$key]);
                }
                else if (strpos($key, 'id') !== false) {
                    $terms_query = $terms_query . "{$key} = :{$key}{$and} ";
                } else {
                    $terms_query = $terms_query . "{$key} LIKE '%{$value}%{$or}' ";
                    $flag_like = true;
                }

                $index++;
            }
        }
        
        $query = "SELECT {$columns} FROM {$this->table} WHERE {$terms_query}";

        $stmt = $this->connection->prepare($query);

        if($flag_like) {
            try {
                $stmt->execute();
            } catch (\Throwable) {
            }    
        }

        if(!$flag_like) {
            try {
                $stmt->execute($terms);
            } catch (\Throwable) {
            }    
        }

        
        if($stmt->rowCount() === 1)
            return $stmt->fetch($this->connection::FETCH_ASSOC);
        
        return $stmt->fetchAll($this->connection::FETCH_ASSOC);
    }

    public function findById(int $id): array
    {   
      return $this->find(terms: ['id' => $id]);
    }

    public function fetch(): array
    {   
        try {

            $query = "SELECT * FROM {$this->table}";

            $stmt = $this->connection->prepare($query);

            $stmt->execute();
    
            if($stmt->rowCount() > 0)
                return $stmt->fetchAll($this->connection::FETCH_ASSOC);
    
        } catch (Exception $exception) {
            return $exception;
        }
        
        return [];
    }

    public function create(array $data): array|bool
    {
        try {  
          $columns = implode(", ", array_keys($data));
          $values = ":" . implode(", :", array_keys($data));
          $stmt = $this->connection->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$values})");

          $stmt->execute($data);

          return $this->findById((int) $this->connection->lastInsertId());
        } catch (\PDOException) {
            return false;
        }
    }

    public function update(array $data): array|bool
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
        } catch (\PDOException) {

            return false;
        }
    }

    public function delete(string $terms, ?array $params): bool
    {
        try {

            $query = "DELETE FROM {$this->table} WHERE {$terms}";
            $stmt = $this->connection->prepare($query);

            $stmt->execute($params);

            return true;

        } catch (Exception $exception) {
            return $exception;
        }
    }
}