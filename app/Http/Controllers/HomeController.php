<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Vista global de Ventas: monitoreo del proceso, estado de pago y cierre del negocio.
     */
    public function ventas(Request $request)
    {
        $query = Contract::with(['client', 'broker'])
            ->orderByRaw("CASE status WHEN 'active' THEN 1 WHEN 'draft' THEN 2 WHEN 'completed' THEN 3 ELSE 4 END")
            ->orderBy('updated_at', 'desc');

        if ($request->filled('estado_negocio')) {
            if ($request->estado_negocio === 'en_proceso') {
                $query->whereIn('status', ['draft', 'active']);
            } elseif ($request->estado_negocio === 'cerrados') {
                $query->where('status', 'completed');
            } elseif ($request->estado_negocio === 'cancelados') {
                $query->where('status', 'cancelled');
            }
        }

        if ($request->filled('estado_pago')) {
            $query->where('payment_status', $request->estado_pago);
        }

        $contracts = $query->paginate(20)->withQueryString();

        return view('ventas.index', compact('contracts'));
    }
}
