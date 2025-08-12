<?php

$no = 1;
foreach ($armada as $armada) : ?>
    <tr>
        <td class="small text-center"><?= $no++ ?></td>
        <td class="d-flex gap-2">
            <button class="btn btn-success btn-sm btn-editArmadaMasuk" data-id="<?= $armada['id_armada']; ?>">
                <i class="fa fa-edit"></i> Edit
            </button>
        </td>
        <td class="small text-center"><?= $armada['nama_petugas'] ?></td>
        <td class="small text-center"><?= $armada['waktu_masuk'] ?></td>
        <td class="small text-center"><?= $armada['tonnase'] ?></td>
        <td class="small text-center"><?= $armada['nama_driver'] ?></td>
        <td class="small text-center"><?= $armada['plat_kendaraan'] ?></td>
        <td class="small text-center"><?= $armada['jenis_armada'] ?></td>
        <td class="small text-center"><?= $armada['waktu_berangkat'] ?></td>
        <td class="small text-center">
            <button type="button" class="btn btn-sm btn-primary lihat-foto-btn" data-id="<?= $armada['id_armada'] ?>">
                Lihat Foto
            </button>
        </td>
    </tr>
<?php endforeach; ?>