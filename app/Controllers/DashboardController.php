<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $role = (string) session()->get('role');

        return match ($role) {
            'administrator' => redirect()->to(site_url('administrator/dashboard')),
            'gudang'        => redirect()->to(site_url('gudang/dashboard')),
            'sub_unit'      => redirect()->to(site_url('sub-unit/dashboard')),
            'manajer_umum'  => redirect()->to(site_url('manajer-umum/dashboard')),
            'direktur'      => redirect()->to(site_url('direktur/dashboard')),
            'pengadaan'     => redirect()->to(site_url('pengadaan/dashboard')),
            default         => redirect()->to(site_url('login'))->with('error', 'Role tidak valid.'),
        };
    }
}
