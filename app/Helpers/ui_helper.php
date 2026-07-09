<?php

if (!function_exists('status_badge')) {
    function status_badge(?string $status): string
    {
        $status = strtolower(trim($status ?? 'draft'));

        $map = [
            'draft'     => ['label' => 'Draft', 'class' => 'bg-secondary'],
            'diajukan'  => ['label' => 'Diajukan', 'class' => 'bg-info text-dark'],
            'dinilai'   => ['label' => 'Sudah Dinilai', 'class' => 'bg-warning text-dark'],
            'diproses'  => ['label' => 'Diproses', 'class' => 'bg-primary'],
            'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-success'],
            'ditolak'   => ['label' => 'Ditolak', 'class' => 'bg-danger'],
            'selesai'   => ['label' => 'Selesai', 'class' => 'bg-dark'],
        ];

        $item = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-secondary'];

        return '<span class="badge ' . $item['class'] . '">' . esc($item['label']) . '</span>';
    }
}

if (!function_exists('validasi_badge')) {
    function validasi_badge(?string $status): string
    {
        $status = strtolower(trim($status ?? 'menunggu'));

        $map = [
            'menunggu'  => ['label' => 'Menunggu', 'class' => 'bg-warning text-dark'],
            'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-success'],
            'ditolak'   => ['label' => 'Ditolak', 'class' => 'bg-danger'],
        ];

        $item = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-secondary'];

        return '<span class="badge ' . $item['class'] . '">' . esc($item['label']) . '</span>';
    }
}