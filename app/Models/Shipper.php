<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    use HasFactory;
    
    protected $table = 'shippers';
    
    protected $fillable = [
        'company_name',
        'address'
    ];
    
    protected $appends = ["contact"];

    /* ************************ ACCESSOR ************************* */

    public function getContactAttribute() {
        return $this->contacts()->where('is_primary' , '=' , true)->first();
    }


    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }


}
