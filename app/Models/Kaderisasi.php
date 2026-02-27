<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kaderisasi extends Model
{
    protected $table = 'kaderisasi';

    protected $fillable = [
        'member_id',
        'level',
        'status',
        'catatan'
    ];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
