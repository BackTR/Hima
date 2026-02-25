<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(){
    $stats = [ 
        'total_anggota' => User::where('role','anggota')->count(),
        'total_pengurus' => User::where('role','pengurus')->count(),
        'total_admin' => User::where('role','admin')->count(),
        'anggota_aktif' => Member::where('status','aktif')->count(),
        'anggota_nonaktif' => Member::where('status','nonaktif')->count(),
        'anggota_alumni' => Member::where('status','alumni')->count(),
        
        ];
    return view('dashboard',compact('stats'));
}
}
