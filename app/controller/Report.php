<?php

class Report extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
        // Batasi akses untuk role 'security'
        if ($_SESSION['role'] === 'security') {
            header('Location: ' . BASE_URL . '/armada_masuk');
            exit;
        }
    }


    public function index()
    {
        $data['judul'] = 'Report';
        $data['name'] = $_SESSION['name'] ?? '';
        $data['username'] = $_SESSION['username'] ?? '';
        $data['userRole'] = $_SESSION['role'] ?? '';

        // Ambil data POST jika ada
        $start = $_POST['startDate'] ?? null;
        $end = $_POST['endDate'] ?? null;

        // Default: kosong
        $data['report'] = [];

        // Jika ada filter tanggal
        if ($start && $end) {
            // Validasi: harus di bulan yang sama
            if (date('Y-m', strtotime($start)) !== date('Y-m', strtotime($end))) {
                $_SESSION['error'] = 'Tanggal tidak valid atau beda bulan.';
                header('Location: ' . BASE_URL . '/report');
                exit;
            }

            // Ambil data dari model
            $data['report'] = $this->model('Armada_models')->getFilteredData($start, $end);
            $data['startDate'] = $start;
            $data['endDate'] = $end;
        }

        // Tampilkan view
        $this->view('templates/header', $data);
        $this->view('report/index', $data);
        $this->view('templates/footer');
    }

    public function getFoto()
    {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo '<p class="text-danger">ID Armada tidak dikirim.</p>';
            return;
        }

        $armada = $this->model('Armada_models')->getById($id);
        if (!$armada) {
            echo '<p class="text-danger">Data armada tidak ditemukan.</p>';
            return;
        }

        $fotoAwal = $armada['foto_kendaraan'] ?? null;
        $fotoSelesai = $armada['foto_selesai'] ?? null;

        echo '<div class="row">';

        // Foto Awal
        echo '<div class="col-md-6">';
        echo '<h6 class="mb-2">Foto Kendaraan Awal</h6>';
        if ($fotoAwal && file_exists($fotoAwal)) {
            echo '<img src="' . BASE_URL . '/' . $fotoAwal . '" class="img-fluid rounded shadow">';
        } else {
            echo '<p class="text-muted">Foto kendaraan awal belum tersedia.</p>';
        }
        echo '</div>';

        // Foto Selesai
        echo '<div class="col-md-6">';
        echo '<h6 class="mb-2">Foto Setelah Selesai</h6>';
        if ($fotoSelesai && file_exists($fotoSelesai)) {
            echo '<img src="' . BASE_URL . '/' . $fotoSelesai . '" class="img-fluid rounded shadow">';
        } else {
            echo '<p class="text-muted">Foto selesai belum tersedia.</p>';
        }
        echo '</div>';

        echo '</div>';
    }

    public function downloadReport()
    {
        $start = $_POST['startDate'] ?? '';
        $end = $_POST['endDate'] ?? '';

        if (!$start || !$end) {
            echo 'Tanggal tidak valid.';
            return;
        }

        // Tambahkan waktu agar mencakup seluruh hari
        $start .= ' 00:00:00';
        $end .= ' 23:59:59';

        $data = $this->model('Armada_models')->getFilteredData($start, $end);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="report_armada.csv"');

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'No',
            'Petugas',
            'Waktu Masuk',
            'Tonnase',
            'Driver',
            'Plat',
            'Jenis Armada',
            'Waktu Berangkat',
            'Waktu Bongkar',
            'Jumlah SDM',
            'Waktu Selesai',
            'Durasi Bongkar'
        ]);

        $no = 1;
        foreach ($data as $row) {
            $startTime = strtotime($row['waktu_bongkar']);
            $endTime = strtotime($row['waktu_selesai']);
            $diff = $endTime - $startTime;

            $durasi = ($diff > 0)
                ? floor($diff / 3600) . ' jam ' . floor(($diff % 3600) / 60) . ' menit'
                : '-';

            fputcsv($output, [
                $no++,
                $row['nama_petugas'],
                $row['waktu_masuk'],
                $row['tonnase'],
                $row['nama_driver'],
                $row['plat_kendaraan'],
                $row['jenis_armada'],
                $row['waktu_berangkat'],
                $row['waktu_bongkar'],
                $row['jumlah_sdm'],
                $row['waktu_selesai'],
                $durasi
            ]);
        }

        fclose($output);
    }
}
