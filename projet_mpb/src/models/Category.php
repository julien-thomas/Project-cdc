<?php

namespace Models;

class Category extends Model {

    /**
     * Returns the list all categories
     *
     * @return array
     */
    public function getCategory(): array
    {
        $req = 'SELECT category FROM categories';
        return $this->findAll($req);
    }
}