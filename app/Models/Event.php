<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'nama_event',
        'tanggal',
        'lokasi',
        'deskripsi',
        'created_by'
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }
}
