<?php

namespace App\Http\Controllers;

use App\Services\RapportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private RapportService $rapportService
    ) {}

    /**
     * Affiche le tableau de bord principal
     */
    public function index()
    {
        $data = $this->rapportService->getDonneesDashboard();
        
        return view('dashboard.index', $data);
    }
}