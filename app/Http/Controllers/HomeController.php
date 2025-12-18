<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $trainer = [
            'name' => 'Aleksandar Durlevic',
            'photo' => asset('images/aca.jpg'),
            'photo_all' => asset('images/viber_slika_2025-11-30_19-41-06-317.jpg'),
            'bio' => 'Certified fitness trainer with a passion for helping clients achieve their health and fitness goals. Specializing in strength training, weight loss, and athletic performance.',
            'clients_count' => 50,
            'experience_years' => 4,
            'services' => [
                'Training Programs',
                'Meal Plans',
                'Online Training'
            ]
        ];

        return view('home', compact('trainer'));
    }
}



















