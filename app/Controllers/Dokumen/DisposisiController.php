<?php

namespace App\Controllers\Dokumen;

use App\Controllers\BaseController;
use App\Services\DisposisiDocumentService;

class DisposisiController extends BaseController
{
    protected DisposisiDocumentService $documentService;

    public function __construct()
    {
        $this->documentService = new DisposisiDocumentService();
        helper(['url', 'form']);
    }

    /**
     * Fallback aman untuk URL /dokumen-disposisi atau /dokumen-disposisi/preview
     * agar tidak terlihat seperti tombol/link rusak saat id usulan tidak terbawa.
     */
    public function index()
    {
        return redirect()
            ->to($this->roleBackUrl())
            ->with('error', 'Pilih data usulan terlebih dahulu sebelum membuka preview dokumen disposisi.');
    }

    public function preview(int $idUsulan)
    {
        try {
            $data = $this->documentService->preparePreview($idUsulan, $this->currentUserId());
            $data['title'] = 'Preview Dokumen Disposisi';
            $data['printMode'] = false;
            $data['returnUrl'] = $this->resolveReturnUrl($idUsulan);
            return view('Dokumen/disposisi_pdf', $data);
        } catch (\Throwable $e) {
            return redirect()->to($this->roleBackUrl($idUsulan))->with('error', $e->getMessage());
        }
    }

    public function download(int $idUsulan)
    {
        try {
            $data = $this->documentService->preparePreview($idUsulan, $this->currentUserId());
            $data['returnUrl'] = $this->resolveReturnUrl($idUsulan);
            $html = view('Dokumen/disposisi_pdf', $data + ['printMode' => true]);
            return $this->response
                ->setHeader('Content-Type', 'text/html; charset=UTF-8')
                ->setHeader('Content-Disposition', 'inline; filename="dokumen-disposisi-' . $idUsulan . '.html"')
                ->setBody($html);
        } catch (\Throwable $e) {
            return redirect()->to($this->roleBackUrl($idUsulan))->with('error', $e->getMessage());
        }
    }

    public function generate(int $idUsulan)
    {
        try {
            $this->documentService->preparePreview($idUsulan, $this->currentUserId());
            return redirect()->to(site_url('dokumen-disposisi/preview/' . $idUsulan))->with('success', 'Preview dokumen disposisi berhasil digenerate. Gunakan tombol Cetak/Simpan PDF pada browser.');
        } catch (\Throwable $e) {
            return redirect()->to($this->roleBackUrl($idUsulan))->with('error', $e->getMessage());
        }
    }

    protected function resolveReturnUrl(?int $idUsulan = null): string
    {
        $return = trim((string) $this->request->getGet('return'));
        if ($return !== '' && !preg_match('#^https?://#i', $return)) {
            return site_url(ltrim($return, '/'));
        }

        $referer = trim((string) $this->request->getServer('HTTP_REFERER'));
        if ($referer !== '' && str_starts_with($referer, site_url())) {
            return $referer;
        }

        return $this->roleBackUrl($idUsulan);
    }

    protected function roleBackUrl(?int $idUsulan = null): string
    {
        $role = strtolower((string) (session()->get('role') ?? ''));

        if ($role === 'direktur') {
            return $idUsulan ? site_url('direktur/validasi/detail/' . $idUsulan) : site_url('direktur/validasi');
        }

        if ($role === 'manajer_umum') {
            return $idUsulan ? site_url('manajer-umum/usulan/detail/' . $idUsulan) : site_url('manajer-umum/usulan');
        }

        if ($role === 'sub_unit') {
            return $idUsulan ? site_url('sub-unit/usulan/detail/' . $idUsulan) : site_url('sub-unit/usulan');
        }

        if ($role === 'gudang') {
            return $idUsulan ? site_url('gudang/hasil-moora/detail/' . $idUsulan) : site_url('gudang/hasil-moora');
        }

        if ($role === 'pengadaan') {
            return site_url('pengadaan/pembelian');
        }

        if ($role === 'administrator') {
            return site_url('administrator/dashboard');
        }

        return site_url('dashboard');
    }
}
