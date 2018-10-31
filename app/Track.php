<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    
	//RELATIONSHIPS
    public function program() {
    	return $this->belongsTo('App\Program');
    }

    protected $fillable = ['id', 'name', 'program_id', 'credits', 'custom_session', 'custom_start_date', 'custom_end_date'];

}
