<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    //
    use HasFactory;

    protected $fillable = ['path', 'todo_id'];

    public function todo():BelongsTo
    {
        return $this->belongsTo(todos::class , 'todo_id' ,'id');
    }
}
