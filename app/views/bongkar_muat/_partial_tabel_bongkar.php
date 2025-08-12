<?php

$no = 1;
foreach ($armada as $armada) : ?>
    <tr>
        <td class="small text-center"><?= $no++ ?></td>
        <td class="d-flex gap-2">
            <button class="btn btn-success btn-sm btn-bongkarMuat" data-id="<?= $armada['id_armada']; ?>">
                <i class="fa fa-edit"></i> Proses
            </button>
        </td>
        <td class="small text-center"><?= $armada['plat_kendaraan'] ?></td>
        <td class="small text-center"><?= $armada['waktu_masuk'] ?></td>
        <td class="small text-center"><?= $armada['tonnase'] ?></td>
        <td class="small text-center"><?= $armada['status'] ?></td>
    </tr>
<?php endforeach; ?>