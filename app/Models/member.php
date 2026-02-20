<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class member extends Model
{
        protected $fillable = [
            'user_id',
            'nim',
            'angkatan',
            'divisi',
            'no_hp',
            'alamat',
            'status',
        ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
