<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BandController extends Controller
{
    public function index()
    {
        // Get only users that have a name and are marked as bands
        $bands = User::where('role', 'band')
                    ->whereNotNull('name')
                    ->get();
        
        return view('bands', compact('bands'));
    }
}
