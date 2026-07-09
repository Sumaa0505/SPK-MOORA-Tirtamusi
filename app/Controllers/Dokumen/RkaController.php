<?php

namespace App\Controllers\Dokumen;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class RkaController extends BaseController
{
    public function show(string $filename)
    {
        $filename = basename($filename);
        $path = WRITEPATH . 'uploads/rka/' . $filename;

        if ($filename === '' || ! is_file($path)) {
            throw PageNotFoundException::forPageNotFound('Dokumen RKA tidak ditemukan.');
        }

        return $this->response->download($path, null)->setFileName($filename);
    }
}
