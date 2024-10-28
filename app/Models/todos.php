<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class todos extends Model
{
    use HasFactory;
    protected $table = 'todos';

    protected $fillable = [
        'name',
        'work',
        'due_date'
    ];

    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'todo_id', 'id');
    }
}
