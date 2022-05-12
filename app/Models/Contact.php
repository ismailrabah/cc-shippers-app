<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    
    protected $table = 'contacts';
    
    protected $fillable = [
        'name',
        'number',
        'is_primary'
    ];

    /**
    * Many to One Relationship to \App\Models\Shipper::class
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function shipper() {
        return $this->belongsTo(\App\Models\Shipper::class,"shipper_id","id");
    }

}
