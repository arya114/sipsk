<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan $user tidak null
        if ($user) {
            if ($user->role == 'user') {
                return redirect()->route('dashboard.user');
            } elseif ($user->role == 'admin') {
                return redirect()->route('dashboard.staf');
            } elseif ($user->role == 'superadmin') {
                return redirect()->route('dashboard.direktur');
            }
        }

        // Jika pengguna tidak terautentikasi, arahkan ke halaman tertentu
        return redirect()->route('login'); // Anda bisa mengganti dengan view lain sesuai kebutuhan
    }

    // Dashboard untuk Pemohon (User)
    public function userDashboard()
    {
        return view('dashboard.user');
    }

    // Dashboard untuk Staf (Admin)
    public function stafDashboard()
    {
        return view('dashboard.staf');
    }

    // Dashboard untuk Direktur (Superadmin)
    public function direkturDashboard()
    {
        return view('dashboard.direktur');
    }
}
