<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title','image','description','category_id'];

    public function category(){
        return $this->belongsTo(Post::class,'category_id');
    }
}
