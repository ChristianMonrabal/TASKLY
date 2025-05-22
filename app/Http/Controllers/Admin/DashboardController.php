<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trabajo;
use App\Models\Valoracion;
use App\Models\Reportes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (Auth::user()->rol_id != 1) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function kpis()
    {
        $totalUsers = User::count();
        $newUsersWeek = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $previousWeekCount = User::whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])->count();
        $growthUsers = $previousWeekCount ? round((($newUsersWeek - $previousWeekCount) / $previousWeekCount) * 100, 2) : 100;

        $totalJobs = Trabajo::count();
        $activeJobs = Trabajo::whereHas('estado', function($q) {
            $q->whereIn('nombre', ['Activo', 'Pendiente']); // Ajusta estados activos que uses
        })->count();

        $completedJobs = Trabajo::whereHas('estado', function($q) {
            $q->whereIn('nombre', ['Completado', 'Finalizado']);
        })->count();

        $avgRating = Valoracion::avg('puntuacion') ?? 0;

        $pendingReports = Reportes::whereHas('estadoReporte', function($q) {
            $q->where('nombre', 'Pendiente');
        })->count();

        return response()->json([
            'totalUsers' => $totalUsers,
            'newUsersWeek' => $newUsersWeek,
            'growthUsers' => $growthUsers,
            'totalJobs' => $totalJobs,
            'activeJobs' => $activeJobs,
            'completedJobs' => $completedJobs,
            'avgRating' => round($avgRating, 2),
            'pendingReports' => $pendingReports,
        ]);
    }

    public function jobsByStatus()
    {
        $jobs = Trabajo::selectRaw('estados.nombre as estado, COUNT(*) as total')
            ->join('estados', 'trabajos.estado_id', '=', 'estados.id')
            ->groupBy('estados.nombre')
            ->get();

        return response()->json($jobs);
    }

    public function userGrowth()
    {
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subDays($i)->format('Y-m-d');  // formato fecha día
        }
    
        $counts = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
    
        $data = [];
        foreach ($dates as $date) {
            $data[] = [
                'label' => Carbon::parse($date)->format('d M'),
                'count' => $counts[$date]->count ?? 0,
            ];
        }
    
        return response()->json($data);
    }
    

    public function topWorkers()
    {
        $workers = User::select('users.id', 'users.nombre', 'users.apellidos', DB::raw('AVG(valoraciones.puntuacion) as avg_rating'))
            ->join('valoraciones', 'valoraciones.trabajador_id', '=', 'users.id')
            ->groupBy('users.id', 'users.nombre', 'users.apellidos')
            ->orderByDesc('avg_rating')
            ->limit(5)
            ->get();
    
        return response()->json($workers);
    }

    public function reportsBySeverity()
    {
        $reports = Reportes::selectRaw('estados.nombre as gravedad, COUNT(*) as total')
            ->join('estados', 'reportes.gravedad', '=', 'estados.id')
            ->groupBy('estados.nombre')
            ->get();

        return response()->json($reports);
    }
}
