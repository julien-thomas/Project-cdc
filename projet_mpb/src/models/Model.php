<?php

namespace Models;
require_once 'Database.php';

abstract class Model //extends Database
{
    protected $bdd;

    public function __construct()
    {
        $this->bdd = \Models\Database::getPdo();
    }


    /**
     * Returns the list of all items
     * 
     * @param string $req
     * @param array $params
     * @return array
     */
    protected function findAll(string $req, array $params = []): array
    {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        return $query->fetchAll();
    }

    /**
     * Returns an item
     * 
     * @param string $req
     * @param array $params
     * @return bool|array
     */
    protected function findOne(string $req, array $params = []): bool|array
    {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        return $query->fetch();
    }

    /**
     * adds an item on a table
     * 
     * @param string $table
     * @param string $columns
     * @param string $values
     * @param $data
     * @return false|int
     */
    protected function addOne(string $table, string $columns, string $values, $data): false|int
    {
        $query = $this->bdd->prepare('INSERT INTO ' . $table . '(' . $columns . ') values (' . $values . ')');

        if ($query->execute($data)) {
            return $this->bdd->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Modifies an item from a table
     * 
     * @param string $table
     * @param array $newData
     * @param string $condition
     * @param string $val
     * @return void
     */
    protected function updateOne(string $table, array $newData, string $condition, string $val): void
    {
        $sets = '';

        foreach ($newData as $key => $value) {
            $sets .= $key . ' = :' . $key . ',';
        }

        $sets = substr($sets, 0, -1);
        $sql = "UPDATE " . $table . " SET " . $sets . " WHERE " . $condition . " = :" . $condition;
        $query = $this->bdd->prepare($sql);

        foreach ($newData as $key => $value) {
            $query->bindValue(':' . $key, $value);
        }

        $query->bindValue(':' . $condition, $val);
        $query->execute();
        $query->closeCursor();
    }

    /**
     * delete an item from the database
     * 
     * @param string $table
     * @param string $condition
     * @param string $value
     * @return void
     */
    protected function deleteOne(string $table, string $condition, string $value): void
    {
        $query = $this->bdd->prepare('DELETE FROM ' . $table . ' WHERE ' . $condition . ' = ?');
        $query->execute([$value]);
        $query->closeCursor();
    }
}
