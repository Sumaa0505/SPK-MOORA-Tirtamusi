<?php

namespace App\Libraries;

class Workflow
{
    public static function nextStatus($current, $action)
    {
        $map = [

            'diajukan' => [
                'verifikasi' => 'diverifikasi',
                'tolak' => 'ditolak'
            ],

            'diverifikasi' => [
                'moora' => 'moora_selesai'
            ],

            'moora_selesai' => [
                'rekomendasi' => 'direkomendasikan'
            ],

            'direkomendasikan' => [
                'bidang' => 'menunggu_direktur_bidang'
            ],

            'menunggu_direktur_bidang' => [
                'approve' => 'menunggu_direktur_utama'
            ],

            'menunggu_direktur_utama' => [
                'approve' => 'menunggu_direktur_umum'
            ],

            'menunggu_direktur_umum' => [
                'disposisi' => 'disposisi_pengadaan'
            ],

            'disposisi_pengadaan' => [
                'proses' => 'diproses_pengadaan'
            ],

            'diproses_pengadaan' => [
                'terima' => 'selesai'
            ]
        ];

        return $map[$current][$action] ?? $current;
    }
}