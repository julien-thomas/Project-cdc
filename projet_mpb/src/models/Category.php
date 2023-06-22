<?php

namespace Models;

class Category extends Model {

    public function getCategory()
    {
        $req = 'SELECT category FROM categories';
        return $this->findAll($req);
    }
}