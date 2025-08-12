<?php

class Armada_models
{
    private $table = 'tb_armada';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
    public function getAllArmada()
    {
        $sql = "SELECT * FROM $this->table WHERE status = :status  ORDER BY id_armada DESC";
        $this->db->query($sql);
        $this->db->bind(':status', 'MASUK');
        return $this->db->resultSet();
    }
    public function addArmada($data)
    {
        $query = "INSERT INTO {$this->table} (
        nama_petugas,
        waktu_masuk,
        tonnase,
        nama_driver,
        plat_kendaraan,
        jenis_armada,
        waktu_berangkat,
        foto_kendaraan,
        status
    ) VALUES (
        :nama_petugas,
        :waktu_masuk,
        :tonnase,
        :nama_driver,
        :plat_kendaraan,
        :jenis_armada,
        :waktu_berangkat,
        :foto_kendaraan,
        :status
    )";
        $this->db->query($query);
        foreach ($data as $key => $val) {
            $this->db->bind($key, $val);
        }
        try {
            $result = $this->db->execute();
            if (!$result) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Query gagal, tapi tidak ada error dari PDO.'
                ]);
                exit;
            }
            echo json_encode([
                'status' => 'success',
                'message' => 'Data berhasil disimpan.'
            ]);
            exit;
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'PDO Error: ' . $e->getMessage()
            ]);
            exit;
        }
    }
    public function getById($id)
    {
        $this->db->query("SELECT * FROM $this->table WHERE id_armada = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
    public function updateArmada($data)
    {
        $query = "UPDATE {$this->table} SET 
                waktu_masuk     = :waktu_masuk,
                tonnase         = :tonnase,
                nama_driver     = :nama_driver,
                plat_kendaraan  = :plat_kendaraan,
                jenis_armada    = :jenis_armada,
                waktu_berangkat = :waktu_berangkat,
                foto_kendaraan  = :foto_kendaraan
              WHERE id_armada = :id_armada";

        $this->db->query($query);

        $this->db->bind('id_armada',      $data['id_armada']);
        $this->db->bind('waktu_masuk',    $data['waktu_masuk']);
        $this->db->bind('tonnase',        $data['tonnase']);
        $this->db->bind('nama_driver',    $data['nama_driver']);
        $this->db->bind('plat_kendaraan', $data['plat_kendaraan']);
        $this->db->bind('jenis_armada',   $data['jenis_armada']);
        $this->db->bind('waktu_berangkat', $data['waktu_berangkat']);
        $this->db->bind('foto_kendaraan', $data['foto_kendaraan']);

        $this->db->execute();

        return $this->db->rowCount(); // Untuk cek apakah update berhasil
    }

    public function countStatusMasuk()
    {
        $this->db->query("SELECT COUNT(*) AS total FROM $this->table WHERE status = :status");
        $this->db->bind(':status', 'MASUK');
        return $this->db->single(); // Ambil hasil COUNT-nya langsung
    }
    public function countStatusProses()
    {
        $this->db->query("SELECT COUNT(*) AS total FROM $this->table WHERE status = :status");
        $this->db->bind(':status', 'PROSES');
        return $this->db->single(); // Ambil hasil COUNT-nya langsung
    }
    public function countStatusSelesaiToday()
    {
        $today = date('Y-m-d'); // Tanggal hari ini dalam format YYYY-MM-DD

        $this->db->query("
        SELECT COUNT(*) AS total 
        FROM $this->table 
        WHERE status = :status 
        AND DATE(Waktu_selesai) = :today
    ");
        $this->db->bind(':status', 'DONE');
        $this->db->bind(':today', $today);

        return $this->db->single(); // Ambil hasil COUNT-nya langsung
    }
    public function countTotal()
    {
        $this->db->query("SELECT COUNT(*) AS total FROM $this->table WHERE status = :status");
        $this->db->bind(':status', 'DONE');
        return $this->db->single(); // Ambil hasil COUNT-nya langsung
    }
    public function getAllArmadaProses()
    {
        $sql = "SELECT * FROM $this->table WHERE status = :status ORDER BY id_armada DESC";
        $this->db->query($sql);
        $this->db->bind(':status', 'PROSES');
        return $this->db->resultSet();
    }

    public function ProsesBongkarMuat($data)
    {
        $query = "UPDATE {$this->table} SET 
                waktu_bongkar     = :waktu_bongkar,
                jumlah_sdm        = :jumlah_sdm,
                status         = :status
              WHERE id_armada = :id_armada";

        $this->db->query($query);

        $this->db->bind('id_armada',     $data['id_armada']);
        $this->db->bind('waktu_bongkar', $data['waktu_bongkar']);
        $this->db->bind('jumlah_sdm',    $data['jumlah_sdm']);
        $this->db->bind('status',        $data['status']);

        $this->db->execute();

        return $this->db->rowCount(); // Untuk cek apakah update berhasil
    }
    public function ProsesDone($data)
    {
        $query = "UPDATE {$this->table} SET 
                waktu_selesai     = :waktu_selesai,
                foto_selesai      = :foto_selesai,
                status            = :status
                WHERE id_armada   = :id_armada";

        $this->db->query($query);

        $this->db->bind('id_armada',     $data['id_armada']);
        $this->db->bind('waktu_selesai', $data['waktu_selesai']);
        $this->db->bind('foto_selesai',    $data['foto_selesai']);
        $this->db->bind('status',        $data['status']);

        $this->db->execute();

        return $this->db->rowCount(); // Untuk cek apakah update berhasil
    }

    public function getAllReport()
    {
        $sql = "SELECT * FROM $this->table WHERE status = :status ORDER BY id_armada DESC";
        $this->db->query($sql);
        $this->db->bind(':status', 'DONE');
        return $this->db->resultSet();
    }

    public function getFilteredData($start, $end)
    {
        $start .= ' 00:00:00';
        $end .= ' 23:59:59';

        $query = "SELECT * FROM $this->table
              WHERE waktu_masuk BETWEEN :start AND :end AND status = :status
              ORDER BY waktu_masuk ASC";
        $this->db->query($query);
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        $this->db->bind(':status', 'DONE');
        return $this->db->resultSet();
    }

    public function getArmadaHarian($start, $end)
    {
        $start .= ' 00:00:00';
        $end .= ' 23:59:59';

        $query = "
        SELECT 
            DATE(waktu_selesai) AS tanggal,
            jenis_armada,
            COUNT(*) AS jumlah
        FROM $this->table
        WHERE waktu_selesai BETWEEN :start AND :end
          AND waktu_selesai != '0000-00-00 00:00:00'
          AND status = :status
        GROUP BY tanggal, jenis_armada
        ORDER BY tanggal ASC
    ";

        $this->db->query($query);
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        $this->db->bind(':status', 'DONE');
        $raw = $this->db->resultSet();

        $result = [];
        foreach ($raw as $row) {
            $tanggal = date('d M', strtotime($row['tanggal']));
            $jenis = strtoupper($row['jenis_armada']);

            if (!isset($result[$tanggal])) {
                $result[$tanggal] = ['JALUR' => 0, 'VENDOR' => 0];
            }

            $result[$tanggal][$jenis] = (int)$row['jumlah'];
        }

        return $result;
    }

    public function getPieArmada($start, $end)
    {
        $start .= ' 00:00:00';
        $end .= ' 23:59:59';

        $query = "
        SELECT jenis_armada, COUNT(*) AS jumlah
        FROM $this->table
        WHERE waktu_selesai BETWEEN :start AND :end
          AND waktu_selesai != '0000-00-00 00:00:00'
          AND status = :status
        GROUP BY jenis_armada
    ";

        $this->db->query($query);
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        $this->db->bind(':status', 'DONE');
        $raw = $this->db->resultSet();

        $result = ['JALUR' => 0, 'VENDOR' => 0];
        foreach ($raw as $row) {
            $jenis = strtoupper($row['jenis_armada']);
            if (isset($result[$jenis])) {
                $result[$jenis] += (int)$row['jumlah'];
            }
        }

        return $result;
    }
}
