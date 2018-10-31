<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{

	//RELATIONSHIPS
    public function tracks() {
    	return $this->hasMany('App\Track');
    }

    //RELATIONSHIPS
    public function school() {
    	return $this->belongsTo('App\School');
    }

    protected $fillable = ['id', 'name', 'school_id'];

}
