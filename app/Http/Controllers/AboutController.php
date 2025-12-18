<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about me page.
     */
    public function index()
    {
        $trainer = [
            'name' => 'Aleksandar Durlevic',
            'photo' => asset('images/aca.jpg'),
            'bio' => 'Certified fitness trainer with a passion for helping clients achieve their health and fitness goals. Specializing in strength training, weight loss, and athletic performance.',
            'clients_count' => 50,
            'experience_years' => 4,
            'services' => [
                'Training Programs',
                'Meal Plans',
                '1-on-1 Coaching',
                'Group Fitness Classes',
                'Nutrition Counseling',
                'Online Training'
            ]
        ];

        return view('about', compact('trainer'));
    }
}

