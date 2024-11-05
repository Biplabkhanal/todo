<?php

namespace App\Models;

use App\Models\todos;
use Database\Factories\TodoImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    //
    use HasFactory;

    protected $fillable = ['path', 'todo_id','imageable_id','imageable_type'];

    public function todo(): BelongsTo
    {
        return $this->belongsTo(todos::class, 'todo_id', 'id');
    }

    protected static function newFactory()
    {
        return TodoImageFactory::new();
    }

    public function imageable(){
        return $this->morphTo();
       }
}
