<?php
if (empty($report)) : ?>
    <!-- Tidak tampilkan apa-apa -->
    <?php else :
    $no = 1;
    foreach ($report as $report) : ?>
        <tr>
            <td class="small text-center"><?= $no++ ?></td>
            <td class="small text-center"><?= $report['nama_petugas'] ?></td>
            <td class="small text-center"><?= $report['waktu_masuk'] ?></td>
            <td class="small text-center"><?= $report['tonnase'] ?></td>
            <td class="small text-center"><?= $report['nama_driver'] ?></td>
            <td class="small text-center"><?= $report['plat_kendaraan'] ?></td>
            <td class="small text-center"><?= $report['jenis_armada'] ?></td>
            <td class="small text-center"><?= $report['waktu_berangkat'] ?></td>
            <td class="small text-center"><?= $report['waktu_bongkar'] ?></td>
            <td class="small text-center"><?= $report['jumlah_sdm'] ?></td>
            <td class="small text-center"><?= $report['waktu_selesai'] ?></td>
            <td class="small text-center">
                <?php
                $start = strtotime($report['waktu_bongkar']);
                $end = strtotime($report['waktu_selesai']);
                $diff = $end - $start;

                if ($diff > 0) {
                    $jam = floor($diff / 3600);
                    $menit = floor(($diff % 3600) / 60);
                    echo "{$jam} jam {$menit} menit";
                } else {
                    echo '<span class="text-muted">-</span>';
                }
                ?>
            </td>

            <td class="small text-center">
                <button type="button" class="btn btn-sm btn-primary lihat-foto-btn"
                    data-id="<?= $report['id_armada'] ?>">
                    Lihat Foto
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>