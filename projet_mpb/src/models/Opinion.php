<?php

namespace Models;

class Opinion extends Model {

    /**
     * Add an opinion
     *
     * @param array $form
     * @return false|int
     */
    public function addOpinion(array $opinion): false|int
    {
        var_dump($_SESSION);
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

    public function getAllOpinionsByProduct(int $id): bool|array
    {
        $req = "SELECT products.id, pseudo, opinions.title, score, opinion, opinions.created_at, status FROM opinions
        INNER JOIN products ON products.id = opinions.products_id
        WHERE products.id = :id AND status = 'on'";
        return $this-> findAll($req, [':id' => $id]);
    }

    public function getAllOpinions(): bool|array
    {
        $req = 'SELECT opinions.id, pseudo, opinions.title, score, opinion, products.name, products.description, products.price, users.firstname, users.lastname, opinions.created_at, status FROM opinions
        INNER JOIN products ON products.id = opinions.products_id
        INNER JOIN users ON users.id = opinions.users_id
        ORDER BY created_at DESC';
        return $this-> findAll($req);
    }

    public function getOneOpinion(int $id): bool|array
    {
        $req = 'SELECT opinions.id, score, opinion, products.name, products.id, users.id, users.firstname, users.lastname, status FROM opinions
        INNER JOIN products ON products.id = opinions.products_id
        INNER JOIN users ON users.id = opinions.users_id
        WHERE opinions.id = :id';
        return $this-> findOne($req, [':id' => $id]);
    }

    public function setOpinion($status, $id) {
        $newData = [
            'status' => $status
        ];
        $val = $id;
        $this->updateOne('opinions', $newData, 'id', $val);
    }
}