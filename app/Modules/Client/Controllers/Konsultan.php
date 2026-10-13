<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;
use Modules\Client\Models\KonsultanModel;

class Konsultan extends BaseController
{
    public function index()
    {
        $model = new KonsultanModel();

        $data = [
            'title' => 'Cari Psikolog',
            'konsultan' => $model->getKonsultanAktif(),
        ];

        return view('Modules\Client\Views\konsultan\index', $data);
    }

    public function detail($id = null)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $model = new KonsultanModel();

        $konsultan = $model->getKonsultanById((int) $id);

        if (empty($konsultan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('Modules\Client\Views\konsultan\detail', [
            'title' => 'Profil Konsultan',
            'item'  => $konsultan,
        ]);
    }
}
