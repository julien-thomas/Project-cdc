<?php

namespace Models;
require_once 'Database.php';

abstract class Model extends Database
{
    // protected $bdd;
    // protected $table;
/*
    public function __construct()
    {
        $this->bdd = \Models\Database::getPdo();
    }

    /**
     * Retourne un item grâce à son identifiant
     * 
     * @param integer $id
     * @return void
     */
    /*    public function findOne(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");

        // On exécute la requête en précisant le paramètre :article_id 
        $query->execute(['id' => $id]);

        // On fouille le résultat pour en extraire les données réelles de l'article
        $item = $query->fetch();

        return $item;
    } */

    /**
     * Supprime un item de la base de données grâce à son identifiant
     * 
     * @param integer $id
     * @return void
     */
    /*  public function delete(int $id): void {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $query->execute(['id' => $id]);
    }
 */
    /**
     * Retourne la liste des items (avec possibilité de classement)
     * 
     * @return array
     */
    /* public function findAll(string $rows, ?string $options = ""): array
    {
        $sql = "SELECT {$rows} FROM {$this->table}";
        if($options) {
            $sql .= $options;
        }
        $resultats = $this->pdo->query($sql);
        // On fouille le résultat pour en extraire les données réelles
        $articles = $resultats->fetchAll();

        return $articles;
    } */

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
     * @return null|array
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
     * @return null|int
     */
    protected function addOne(string $table, string $columns, string $values, $data): ?int
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
