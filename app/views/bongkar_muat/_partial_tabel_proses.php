<?php

$no = 1;
foreach ($proses as $proses) : ?>
    <tr>
        <td class="small text-center"><?= $no++ ?></td>
        <td class="d-flex gap-2">
            <button class="btn btn-primary btn-sm btn-prosesDone" data-id="<?= $proses['id_armada']; ?>">
                <i class="fa fa-check"></i> Done
            </button>
        </td>
        <td class="small text-center"><?= $proses['plat_kendaraan'] ?></td>
        <td class="small text-center"><?= $proses['jumlah_sdm'] ?></td>
        <td class="small text-center"><?= $proses['waktu_bongkar'] ?></td>
        <td class="small text-center"><?= $proses['status'] ?></td>
    </tr>
<?php endforeach; ?>