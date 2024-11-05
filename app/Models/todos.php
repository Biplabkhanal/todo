<?php

namespace App\Models;

use App\Models\Image;
use Database\Factories\TodoFactory;
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
        return $this->morphMany(Image::class, 'imageable');
    }

    protected static function newFactory()
{
    return TodoFactory::new();
}
}
