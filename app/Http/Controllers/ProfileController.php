<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schedule = null;
        
        if ($user->role === 'student' && $user->studend_class && $user->studend_class !== 'none') {
            $tomorrow = Carbon::tomorrow();
            
            // Получаем день недели для завтрашнего дня (1 - понедельник, 5 - пятница)
            $dayOfWeek = $tomorrow->dayOfWeek;
            
            $daysMap = [
                1 => 'Понедельник',
                2 => 'Вторник',
                3 => 'Среда',
                4 => 'Четверг',
                5 => 'Пятница',
                6 => 'Суббота',
                7 => 'Воскресенье'
            ];
            
            $dayName = $daysMap[$dayOfWeek] ?? null;
            
            // Получаем начало недели завтрашнего дня
            $weekStart = $tomorrow->copy()->startOfWeek();
            
            if ($dayName && in_array($dayName, ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'])) {
                $schedule = Schedule::where('class', $user->studend_class)
                    ->where('day', $dayName)
                    ->whereDate('week_start_date', $weekStart)
                    ->orderBy('lesson_number')
                    ->get();
            }
        }
        
        return view('profile.index', compact('user', 'schedule'));
    }
}