<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'title'      => 'Dashboard SIAGA',
            'breadcrumb' => 'Utama / Dashboard',
        ];

        return view('dashboard/index', $data);
    }
}
