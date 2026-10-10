<?php

namespace Core;

use PDO;

abstract class Model
{
    protected $table;
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public function findBy($column, $value)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = ?", [$value]);
        return $stmt->fetchAll();
    }
    
    public function findOneBy($column, $value)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1", [$value]);
        return $stmt->fetch();
    }

    public function count()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as cnt FROM {$this->table}");
        $row = $stmt->fetch();
        return (int) $row['cnt'];
    }

    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $this->db->query($sql, array_values($data));
        return $this->db->getConnection()->lastInsertId();
    }

    public function update($id, array $data)
    {
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = ?";
        
        $values = array_values($data);
        $values[] = $id;
        
        $stmt = $this->db->query($sql, $values);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->rowCount();
    }
}
