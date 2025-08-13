<?php

class Armada_masuk extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
        // Batasi akses untuk role 'security'
        if ($_SESSION['role'] === 'user') {
            header('Location: ' . BASE_URL . '/bongkar_muat');
            exit;
        }
    }
    public function index()
    {
        $data['judul'] = 'Armada Masuk';
        // Ambil data session user
        $data['name'] = $_SESSION['name'] ?? '';
        $data['username'] = $_SESSION['username'] ?? '';
        $data['userRole'] = $_SESSION['role'] ?? '';
        $data['armada'] = $this->model('Armada_models')->getAllArmada();
        // Load view
        $this->view('templates/header', $data);
        $this->view('armada_masuk/index', $data);
        $this->view('templates/footer');
    }
    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Tangkap file
            $foto = $_FILES['foto_kendaraan'];
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
                    'nama_petugas' => $_POST['nama_petugas'],
                    'waktu_masuk' => $_POST['waktu_masuk'],
                    'tonnase' => $_POST['tonnase'],
                    'nama_driver' => $_POST['nama_driver'],
                    'plat_kendaraan' => $_POST['plat_kendaraan'],
                    'jenis_armada' => $_POST['jenis_armada'],
                    'waktu_berangkat' => $_POST['waktu_berangkat'],
                    'foto_kendaraan' => $pathSimpan, // Simpan path file
                    'status' => 'MASUK'
                ];

                if ($this->model('Armada_models')->addArmada($data) > 0) {
                    Flasher::setFlash('berhasil', 'ditambahkan', 'success');
                } else {
                    Flasher::setFlash('gagal', 'ditambahkan', 'danger');
                }
            } else {
                Flasher::setFlash('gagal', 'upload foto', 'danger');
            }

            header('Location: ' . BASE_URL . '/armada_masuk');
            exit;
        }
    }

    // Controller: ArmadaMasukController.php

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

        $fotoRelatif = $armada['foto_kendaraan']; // Sudah termasuk 'public/img/...'
        $fotoPath =  $fotoRelatif; // Jangan pakai ../public/ dua kali

        if (!file_exists($fotoPath)) {
            echo '<p class="text-danger">Foto tidak ditemukan di ' . $fotoPath . '</p>';
            return;
        }

        echo '<img src="' . BASE_URL . '/' . $fotoRelatif . '" class="img-fluid rounded shadow">';
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
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldFoto     = $_POST['old_foto_kendaraan'];
            $pathSimpan  = $oldFoto;

            if (!empty($_FILES['foto_kendaraan']['tmp_name'])) {
                error_log('✅ File diterima: ' . $_FILES['foto_kendaraan']['name']);

                $foto         = $_FILES['foto_kendaraan'];
                $namaFile     = basename($foto['name']);
                $tmpFile      = $foto['tmp_name'];
                $folderTujuan = 'img/';
                $pathBaru     = $folderTujuan . uniqid() . '_' . $namaFile;

                if (!is_dir($folderTujuan)) {
                    mkdir($folderTujuan, 0777, true);
                }

                $ext = strtolower(pathinfo($pathBaru, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                            $image = @imagecreatefromjpeg($tmpFile);
                            if ($image) {
                                imagejpeg($image, $pathBaru, 70);
                            }
                            break;
                        case 'png':
                            $image = @imagecreatefrompng($tmpFile);
                            if ($image) {
                                imagepng($image, $pathBaru, 6);
                            }
                            break;
                    }
                    if (isset($image)) {
                        imagedestroy($image);
                        if (file_exists($oldFoto)) {
                            unlink($oldFoto);
                        }
                        $pathSimpan = $pathBaru;
                        error_log('✅ Foto baru diproses: ' . $pathSimpan);
                    } else {
                        error_log('❌ Gagal proses image dari tmpFile');
                    }
                } else {
                    Flasher::setFlash('format tidak valid', 'foto kendaraan', 'danger');
                    header('Location: ' . BASE_URL . '/armada_masuk');
                    exit;
                }
            } else {
                error_log('ℹ️ Tidak ada foto baru diupload. Menggunakan foto lama: ' . $oldFoto);
            }

            // Siapkan data untuk update
            $data = [
                'id_armada'       => $_POST['id_armada'],
                'waktu_masuk'     => $_POST['waktu_masuk'],
                'tonnase'         => $_POST['tonnase'],
                'nama_driver'     => $_POST['nama_driver'],
                'plat_kendaraan'  => $_POST['plat_kendaraan'],
                'jenis_armada'    => $_POST['jenis_armada'],
                'waktu_berangkat' => $_POST['waktu_berangkat'],
                'foto_kendaraan'  => $pathSimpan
            ];

            $updated = $this->model('Armada_models')->updateArmada($data);

            if ($updated > 0) {
                Flasher::setFlash('berhasil', 'diupdate', 'success');
                error_log('✅ Update sukses. Baris terpengaruh: ' . $updated);
            } elseif ($updated === 0) {
                Flasher::setFlash('tidak ada perubahan data', '', 'info');
                error_log('ℹ️ Tidak ada data yang berubah.');
            } else {
                Flasher::setFlash('gagal', 'diupdate', 'danger');
                error_log('❌ Gagal update ke database.');
            }

            header('Location: ' . BASE_URL . '/armada_masuk');
            exit;
        }
    }
}
