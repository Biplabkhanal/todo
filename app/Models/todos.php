<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function image()
    {
        return $this->hasMany(Image::class, 'todo_id', 'id');
    }
}
