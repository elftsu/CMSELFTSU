<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Categories;

class Post extends Model
{
    
    protected $fillable =["title" ,"category_id"] ;
    public function Category() {
  
 return $this->belongsTo(Categories::class);

    //  return $this->belongsTo('App\Categories','id');
      //  return $this->belongTo('App\categories', 'id', 'category_id')->get() ;

    }
}
