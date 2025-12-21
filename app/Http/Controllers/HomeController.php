<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $trainer = [
            'name' => 'Александар Дурлевић',
            'photo' => asset('images/aca.jpg'),
            'photo_all' => asset('images/viber_slika_2025-11-30_19-41-06-317.jpg'),
            'bio' => 'Сертификовани фитнес тренер са страшћу да помогне клијентима да остваре своје здравствене и фитнес циљеве. Специјализован за тренинг снаге, мршављење и спортске перформансе.',
            'clients_count' => 50,
            'experience_years' => 5,
            'services' => [
                'Програми обуке',
                'Планови оброка',
                'Онлајн обука'
            ]
        ];

        return view('home', compact('trainer'));
    }
}





















