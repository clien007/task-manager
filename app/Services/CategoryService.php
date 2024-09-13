<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{

    public function getAllCategory($limit = NULL){

        $category = new Category();

        if($limit !== NULL) $category->orderBy('created_at', 'desc')->take($limit);

        return $category->get();
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data)
    {
        $category->update($data);
        return $category;
    }

}
