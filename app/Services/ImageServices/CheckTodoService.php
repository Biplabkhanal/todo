<?php

namespace App\Services\ImageServices;
use App\Models\todos;

class CheckTodoService
{

public static function checkTodo($images){
    $images->getCollection()->transform(function($image){
        $image->is_todo = $image->imageable instanceof todos;
            return $image;
        });

        return $images;
}
}

?>
