<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    
	//RELATIONSHIPS
    public function programs() {
    	return $this->hasMany('App\Program');
    }

    //RELATIONSHIPS
    public function sessions() {
    	return $this->hasMany('App\Session');
    }

    protected $fillable = ['id', 'name'];

}
