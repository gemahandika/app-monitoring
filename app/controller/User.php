<?php

class User extends Controller
{

    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['superadmin', 'admin'])) {
            header('Location: ' . BASE_URL . '/home');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'User';
        $userRole = $_SESSION['role'];
        $username = $_SESSION['username'];
        $name = $_SESSION['name'];

        $data['name'] = $name;
        $data['username'] = $username;
        $data['userRole'] = $userRole; // <-- Tambahkan baris ini
        $data['user'] = $this->model('User_models')->getAllUsers();
        $data['cabang'] = $this->model('Cabang_models')->getAllCabang();
        $this->view('templates/header', $data);
        $this->view('user/index', $data);
        $this->view('templates/footer');
    }

    public function tambahUser()
    {
        $result = $this->model('User_models')->tambahDataUser($_POST);

        if ($result === 'duplicate') {
            Flasher::setFlash('Opppss!!', 'User Sudah Ada', 'error');
            header('Location: ' . BASE_URL . '/user');
            exit;
        }

        if ($result > 0) {
            Flasher::setFlash('User', 'Berhasil ditambahkan', 'success');
            header('Location: ' . BASE_URL . '/user');
            exit;
        } else {
            Flasher::setFlash('User', 'Gagal ditambahkan', 'error');
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }

    public function editUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['edit-id'],
                'username' => $_POST['edit-username'],
                'role' => $_POST['edit-role'],
                'name' => $_POST['edit-name'],
                'cabang' => $_POST['edit-cabang'],
                'status' => $_POST['edit-status']
            ];

            $result = $this->model('User_models')->updateDataUser($data);
            if ($result !== false) {
                Flasher::setFlash('User Berhasil', 'diUpdate', 'success');
                header('Location: ' . BASE_URL . '/user');
                exit;
            } else {
                Flasher::setFlash('Gagal', 'diUpdate', 'error');
                header('Location: ' . BASE_URL . '/user');
                exit;
            }
        }
    }

    public function editPass()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? trim($_POST['id']) : null;
            $password = isset($_POST['edit-pass']) ? trim($_POST['edit-pass']) : null;
            // Validasi sederhana
            if (!$id || !$password) {
                Flasher::setFlash('ID atau Password tidak boleh kosong', '', 'error');
                header('Location: ' . BASE_URL . '/user');
                exit;
            }
            $data = [
                'id' => $id,
                'password' => $password
            ];
            $result = $this->model('User_models')->updateDataPass($data);
            if ($result > 0) {
                Flasher::setFlash('Password Berhasil', 'diUpdate', 'success');
            } else {
                Flasher::setFlash('Password Gagal', 'diUpdate', 'error');
            }
            header('Location: ' . BASE_URL . '/user');
            exit;
        } else {
            // Jika akses langsung tanpa POST
            Flasher::setFlash('Akses tidak valid', '', 'error');
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }
}
