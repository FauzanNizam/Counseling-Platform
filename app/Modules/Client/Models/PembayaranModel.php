<?php

namespace Modules\Client\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id_pembayaran';
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'id_konsultasi',
        'tanggal_bayar',
        'metode',
        'jumlah',
        'status_bayar',
        'bukti_bayar',
        'kode_transaksi',
    ];
}
