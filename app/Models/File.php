<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'mime_type',
        'size',
        'name',
        'name_uploaded',
        'path'
    ];

    public function fileable()
    {
        return $this->morphTo();
    }
}
