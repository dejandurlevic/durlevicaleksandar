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
            'name' => 'Александар Дурлевић',
            'photo' => asset('images/aca.jpg'),
            'bio' => 'Сертификовани фитнес тренер са страшћу да помогне клијентима да остваре своје здравствене и фитнес циљеве. Специјализован за тренинг снаге, мршављење и спортске перформансе.',
            'clients_count' => 50,
            'experience_years' => 5,
            'services' => [
                'Training Programs',
                'Meal Plans',
                '1-on-1 Coaching',
                'Nutrition Counseling',
                'Online Training'
            ]
        ];

        return view('about', compact('trainer'));
    }
}

