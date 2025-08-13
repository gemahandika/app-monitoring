<?php
require_once '../app/core/Flasher.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

class Home extends Controller
{
    public function index()
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


        $data['judul'] = 'Home';
        $data['userRole'] = $_SESSION['role'];
        $data['username'] = $_SESSION['username'];
        $data['name'] = $_SESSION['name'];

        // load data
        $armadaModel = $this->model('Armada_models');
        $data['armada'] = $armadaModel->getAllArmada();
        $data['masuk'] = $armadaModel->countStatusMasuk();
        $data['proses'] = $armadaModel->countStatusProses();
        $data['selesai'] = $armadaModel->countStatusSelesaiToday();
        $data['total'] = $armadaModel->countTotal();

        // chart data
        $start = $_GET['start'] ?? date('Y-m-d', strtotime('-30 days'));
        $end = $_GET['end'] ?? date('Y-m-d');

        // Validasi tanggal
        if (strtotime($start) > strtotime($end)) {
            [$start, $end] = [$end, $start];
        }
        $chartRaw = $armadaModel->getArmadaHarian($start, $end);
        $pieRaw = $armadaModel->getPieArmada($start, $end);

        $labels = array_keys($chartRaw);
        $jalurData = [];
        $vendorData = [];

        foreach ($labels as $label) {
            $jalurData[] = $chartRaw[$label]['JALUR'] ?? 0;
            $vendorData[] = $chartRaw[$label]['VENDOR'] ?? 0;
        }

        $data['chart'] = [
            'labels' => $labels,
            'jalur' => $jalurData,
            'vendor' => $vendorData
        ];

        $data['pie'] = $pieRaw;

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }
}
