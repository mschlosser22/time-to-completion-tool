<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    
    //RELATIONSHIPS
    public function school() {
    	return $this->belongsTo('App\School');
    }

    protected $fillable = ['id', 'name', 'school_id', 'start_date', 'end_date'];

    protected $dates = [
        'start_date',
        'end_date'
    ];

}
