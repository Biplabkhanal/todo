<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class todos extends Model
{
    use HasFactory;
    protected $table = 'todos';

    protected $fillable = [
        'name',
        'work',
        'duedate'
    ];

    public function image() :HasOne
    {
        return $this->hasOne(Image::class ,'todo_id' ,'id');
    }
}
