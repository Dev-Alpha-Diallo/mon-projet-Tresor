<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        // Notification Wave
        $data['notifPaiement'] = cache()->get('notif_admin_paiement');

        return view('admin.dashboard.index', $data);
    }
}
