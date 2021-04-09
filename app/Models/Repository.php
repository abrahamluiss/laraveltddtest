<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasFactory;
    protected $fillable = [
        'url', 'description'
    ];
    //protected $guarder = [];
    public function user()
    {
        return $this->belongsTo(User::class);//pertenece a usuario
    }
}
