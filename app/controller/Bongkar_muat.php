<?php

class Bongkar_muat extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
    }
    public function index()
    {
        $data['judul'] = 'Bongkar Muat';
        // Ambil data session user
        $data['name'] = $_SESSION['name'] ?? '';
        $data['username'] = $_SESSION['username'] ?? '';
        $data['userRole'] = $_SESSION['role'] ?? '';
        $data['armada'] = $this->model('Armada_models')->getAllArmada();
        $data['proses'] = $this->model('Armada_models')->getAllArmadaProses();
        // Load view
        $this->view('templates/header', $data);
        $this->view('bongkar_muat/index', $data);
        $this->view('templates/footer');
    }



    public function getArmadaMasukById()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_armada'];
            $data = $this->model('Armada_models')->getById($id);

            // Pastikan datanya bisa di-encode
            if (!$data) {
                http_response_code(404);
                echo json_encode(['error' => 'Data tidak ditemukan']);
                exit;
            }

            header('Content-Type: application/json');
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            exit;
        }
    }
    public function bongkarMuat()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_armada' => $_POST['id_armada'],
                'waktu_bongkar' => date('Y-m-d H:i:s'),
                'jumlah_sdm' => $_POST['jumlah_sdm'],
                'status' => 'PROSES'
            ];
            if ($this->model('Armada_models')->ProsesBongkarMuat($data) > 0) {
                Flasher::setFlash('berhasil', 'diupdate', 'success');
            } else {
                Flasher::setFlash('gagal', 'diupdate', 'danger');
            }
            header('Location: ' . BASE_URL . '/bongkar_muat');
            exit;
        }
    }
    public function done()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Tangkap file
            $foto = $_FILES['foto_selesai'];
            $namaFile = $foto['name'];
            $tmpFile = $foto['tmp_name'];

            // Tentukan path penyimpanan
            $folderTujuan = 'img/';
            $pathSimpan = $folderTujuan . uniqid() . '_' . basename($foto['name']);

            // Buat folder jika belum ada
            if (!is_dir($folderTujuan)) {
                mkdir($folderTujuan, 0777, true);
            }

            // Pindahkan file
            if (move_uploaded_file($tmpFile, $pathSimpan)) {
                $data = [
                    'id_armada' => $_POST['id_armada'],
                    'waktu_selesai' => date('Y-m-d H:i:s'),
                    'foto_selesai' => $pathSimpan, // Simpan path file
                    'status' => 'DONE'
                ];

                if ($this->model('Armada_models')->ProsesDone($data) > 0) {
                    Flasher::setFlash('berhasil', 'ditambahkan', 'success');
                } else {
                    Flasher::setFlash('gagal', 'ditambahkan', 'danger');
                }
            } else {
                Flasher::setFlash('gagal', 'upload foto', 'danger');
            }

            header('Location: ' . BASE_URL . '/bongkar_muat');
            exit;
        }
    }
}
