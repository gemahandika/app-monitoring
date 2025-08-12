<main>
    <div class="container-fluid px-4">
        <h5 class="mt-4 fw-bold" style="border-bottom: 1px solid black;">Bongkar Muat</h5>

        <!-- 🔘 CARD UNTUK TOMBOL AKSI -->
        <div class="card mb-3">
            <div class="card-body">

                <?php Flasher::flash(); ?>
                <?php if (isset($_SESSION['flash_stack'])): ?>
                    <?php foreach ($_SESSION['flash_stack'] as $flash): ?>
                        <script>
                            Swal.fire({
                                icon: '<?= $flash['tipe']; ?>',
                                title: '<?= $flash['pesan']; ?>',
                                text: '<?= $flash['aksi']; ?>',
                                confirmButtonText: 'Oke',
                                allowOutsideClick: false
                            });
                        </script>
                    <?php endforeach;
                    unset($_SESSION['flash_stack']); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- 🔽 TABLE KIRI -->
                    <div class="col-md-6">
                        <div class="card mb-3 h-100">
                            <div class="card-body" style="overflow-x:auto;">
                                <span class="fw-bold">Armada masuk</span>
                                <table id="example" class="display nowrap table table-striped">
                                    <thead>
                                        <tr class="bg-cantik text-white">
                                            <th class="small text-center">No</th>
                                            <th class="small text-center">Action</th>
                                            <th class="small text-center">Plat Kendaraan</th>
                                            <th class="small text-center">Jam Masuk</th>
                                            <th class="small text-center">Tonase</th>
                                            <th class="small text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php require_once '../app/views/bongkar_muat/_partial_tabel_bongkar.php'; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- 🔽 TABLE KANAN -->
                    <div class="col-md-6">
                        <div class="card mb-3 h-100">
                            <div class="card-body" style="overflow-x:auto;">
                                <span class="fw-bold">Proses armada</span>
                                <table id="example2" class="display nowrap table table-bordered">
                                    <thead>
                                        <tr class="bg-button1 text-white">
                                            <th class="small text-center">No</th>
                                            <th class="small text-center">Action</th>
                                            <th class="small text-center">Plat Kendaraan</th>
                                            <th class="small text-center">Jlh SDM</th>
                                            <th class="small text-center">Waktu Bongkar</th>
                                            <th class="small text-center">status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php require_once '../app/views/bongkar_muat/_partial_tabel_proses.php'; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL BONGKAR MUAT -->
        <div class="modal fade" id="modalBongkarMuat" tabindex="-1" aria-labelledby="modalBongkarMuatLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrolable">
                <div class="modal-content">
                    <form action="<?= BASE_URL; ?>/bongkar_muat/bongkarMuat" id="formBongkarMuat" method="POST">
                        <div class="modal-header bg-cantik text-white text-small">
                            <h5 class="modal-title" id="modalBongkarMuatLabel">Proses Bongkar Muat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <input type="hidden" name="id_armada" id="edit-id_armada">
                            <div class="row">
                                <div class="col-6 col-md-6 mb-3">
                                    <label for="waktu_masuk" class="form-label fw-bold">Waktu Masuk : </label>
                                    <input type="text" name="waktu_masuk" id="edit-waktu_masuk" class="form-control bg-yellowslow" required readonly>
                                </div>
                                <div class="col-6 col-md-6 mb-3">
                                    <label for="tonnase" class="form-label fw-bold">Tonase :</label>
                                    <input type="text" name="tonnase" id="edit-tonnase" class="form-control bg-yellowslow" required readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6 mb-3">
                                    <label for="nama_driver" class="form-label fw-bold">Nama Driver :</label>
                                    <input type="text" name="nama_driver" id="edit-nama_driver" class="form-control bg-yellowslow" required>
                                </div>
                                <div class="col-6 col-md-6 mb-3">
                                    <label for="plat_kendaraan" class="form-label fw-bold">Plat Kendaraan :</label>
                                    <input type="text" name="plat_kendaraan" id="edit-plat_kendaraan" class="form-control bg-yellowslow" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6 mb-3">
                                    <label for="jenis_armada" class="form-label fw-bold">Jenis Armada :</label>
                                    <input type="text" name="jenis_armada" id="edit-jenis_armada" class="form-control bg-yellowslow" required>
                                </div>
                                <div class="col-6 col-md-6 mb-3">
                                    <label for="jumlah_sdm" class="form-label fw-bold">Jlh SDM :</label>
                                    <input type="number" name="jumlah_sdm" id="edit-jumlah_sdm" class="form-control" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn bg-cantik text-white button-cantik">Proses</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL SELESAI MUAT -->
        <div class="modal fade" id="modalProsesDone" tabindex="-1" aria-labelledby="modalProsesDoneLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrolable">
                <div class="modal-content">
                    <form action="<?= BASE_URL; ?>/bongkar_muat/done" id="formProsesDone" enctype="multipart/form-data" method="POST">
                        <div class="modal-header bg-button1 text-white text-small">
                            <h5 class="modal-title text-small" id="modalProsesDoneLabel">Submit Proses Selesai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <input type="hidden" name="id_armada" id="proses-id_armada">
                            <div class="col-md-12 mb-3">
                                <label for="plat_kendaraan" class="form-label ">Plat Kendaraan :</label>
                                <input type="text" name="plat_kendaraan" id="proses-plat_kendaraan" class="form-control bg-yellowslow" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="foto_selesai" class="form-label ">Foto Selesai :</label>
                                <input type="file" name="foto_selesai" id="proses-foto_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn bg-button1 text-white button1-hover">Done</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


</main>