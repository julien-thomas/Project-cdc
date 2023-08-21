<?php

namespace Models;

class Opinion extends Model {

    /**
     * Adds an opinion to the table opinions
     *
     * @param array $opinion
     * @return false|int
     */
    public function addOpinion(array $opinion): false|int
    {
        $data = [
        ':score' => $opinion['score'],
        ':pseudo' => $opinion['pseudo'],
        ':title' => $opinion['title'],
        ':opinion' => $opinion['opinion'],
        ':products_id' => $_GET['id'],
        ':users_id' => $_SESSION['user']['id']
        ];
        return $this->addOne('opinions', 'score, pseudo, title, opinion, products_id, users_id', ':score, :pseudo, :title, :opinion, :products_id, :users_id', $data);
    }

    /**
     * Get all opinions by product's id
     *
     * @param int $id
     * @return bool|array
     */
    public function getAllOpinionsByProduct(int $id): bool|array
    {
        $req = "SELECT products.id, pseudo, opinions.title, score, opinion, opinions.created_at, status FROM opinions
        INNER JOIN products ON products.id = opinions.products_id
        WHERE products.id = :id AND status = 'on'";
        return $this-> findAll($req, [':id' => $id]);
    }

    /**
     * Get all opinions
     *
     * @return bool|array
     */
    public function getAllOpinions(): bool|array
    {
        $req = 'SELECT opinions.id, pseudo, opinions.title, score, opinion, products.name, products.description, products.price, users.firstname, users.lastname, opinions.created_at, status FROM opinions
        INNER JOIN products ON products.id = opinions.products_id
        INNER JOIN users ON users.id = opinions.users_id
        ORDER BY created_at DESC';
        return $this-> findAll($req);
    }

    /**
     * Get one opinion by its id
     *
     * @param int $id
     * @return bool|array
     */
    public function getOneOpinion(int $id): bool|array
    {
        $req = 'SELECT opinions.id, score, opinion, products.name, products.id, users.id, users.firstname, users.lastname, status FROM opinions
        INNER JOIN products ON products.id = opinions.products_id
        INNER JOIN users ON users.id = opinions.users_id
        WHERE opinions.id = :id';
        return $this-> findOne($req, [':id' => $id]);
    }

    /**
     * sets the opinion visible or not
     * 
     * @param string $status
     * @param int $id
     * @return void
     */
    public function setOpinion(string $status, int $id): void {
        $newData = [
            'status' => $status
        ];
        $val = $id;
        $this->updateOne('opinions', $newData, 'id', $val);
    }

    /**
     * delete the opinion by its id
     * 
     * @param int $id
     * @return void
     */
    public function deleteOpinionById(int $opinion_id): void {
        $this->deleteOne('opinions', 'id', $opinion_id);
    }
}