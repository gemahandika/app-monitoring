<main>
    <div class="container-fluid px-4">
        <h5 class="mt-4 fw-bold" style="border-bottom: 1px solid black;">Armada Masuk</h5>
        <div class="card mb-4 mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn bg-cantik text-white btn-sm button-cantik" data-bs-toggle="modal" data-bs-target="#modalTambahArmada">
                        <i class="fa fa-plus"></i> Tambah Data
                    </button>
                </div>
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
            <div class="card-body">
                <div id="tableWrapper">
                    <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr class="bg-cantik text-white">
                                <th class="small text-center">No</th>
                                <th class="small text-center">Action</th>
                                <th class="small text-center">Nama Petugas</th>
                                <th class="small text-center">Waktu Masuk</th>
                                <th class="small text-center">Tonnase</th>
                                <th class="small text-center">Nama Driver</th>
                                <th class="small text-center">Plat Kendaraan</th>
                                <th class="small text-center">Jenis Armada</th>
                                <th class="small text-center">Waktu Berangkat</th>
                                <th class="small text-center">Poto Kendaraan</th>
                            </tr>
                        </thead>
                        <tbody id="karyawanResult">
                            <?php
                            extract($data);
                            require_once '../app/views/armada_masuk/_partial_tabel_armada.php';
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Modal Preview poto -->
                <div class="modal fade" id="modalFotoKendaraan" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-body text-center" id="fotoKendaraanContent">
                                <!-- Foto akan dimasukkan lewat AJAX -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Tambah -->
                <div class="modal fade" id="modalTambahArmada" tabindex="-1" aria-labelledby="modalTambahArmadaLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <form action="<?= BASE_URL; ?>/tambah" id="formTambahArmada" enctype="multipart/form-data" method="POST">
                                <div class="modal-header bg-cantik text-white">
                                    <h5 class="modal-title" id="modalTambahArmadaLabel">Tambah Data Armada</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_petugas" class="form-label fw-bold">Nama Petugas :</label>
                                            <input type="text" name="nama_petugas" id="tambah-nama_petugas" class="form-control bg-success text-white fw-bold" value="<?= $name ?>" required readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="waktu_masuk" class="form-label fw-bold">Waktu Masuk : </label>
                                            <input type="datetime-local" name="waktu_masuk" id="tambah-waktu_masuk" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tonnase" class="form-label fw-bold">Tonase :</label>
                                            <input type="text" name="tonnase" id="tambah-tonnase" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_driver" class="form-label fw-bold">Nama Driver :</label>
                                            <input type="text" name="nama_driver" id="tambah-nama_driver" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="plat_kendaraan" class="form-label fw-bold">Plat Kendaraan :</label>
                                            <input type="text" name="plat_kendaraan" id="tambah-plat_kendaraan" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="jenis_armada" class="form-label fw-bold">Jenis Armada :</label>
                                            <select name="jenis_armada" id="tambah-jenis_armada" class="form-select" required>
                                                <option value="">Pilih Jenis Armada</option>
                                                <option value="Vendor">Vendor</option>
                                                <option value="Jalur">Jalur</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="waktu_berangkat" class="form-label fw-bold">Waktu Berangkat :</label>
                                            <input type="datetime-local" name="waktu_berangkat" id="tambah-waktu_berangkat" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="foto_kendaraan" class="form-label fw-bold">Foto Kendaraan :</label>
                                            <input type="file" name="foto_kendaraan" id="tambah-foto_kendaraan" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn bg-cantik text-white button-cantik">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Armada masuk -->
                <div class="modal fade" id="modalEditArmadaMasuk" tabindex="-1" aria-labelledby="modalEditArmadaMasukLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <form action="<?= BASE_URL; ?>/armada_masuk/edit" id="formEditArmadaMasuk" enctype="multipart/form-data" method="POST">
                                <div class="modal-header bg-cantik text-white">
                                    <h5 class="modal-title" id="modalEditArmadaMasukLabel">Edit Data Armada Masuk</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                    <input type="hidden" name="id_armada" id="edit-id_armada">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_petugas" class="form-label fw-bold">Nama Petugas :</label>
                                            <input type="text" name="nama_petugas" id="edit-nama_petugas" class="form-control bg-success text-white fw-bold" value="<?= $name ?>" required readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="waktu_masuk" class="form-label fw-bold">Waktu Masuk : </label>
                                            <input type="datetime-local" name="waktu_masuk" id="edit-waktu_masuk" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tonnase" class="form-label fw-bold">Tonase :</label>
                                            <input type="text" name="tonnase" id="edit-tonnase" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_driver" class="form-label fw-bold">Nama Driver :</label>
                                            <input type="text" name="nama_driver" id="edit-nama_driver" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="plat_kendaraan" class="form-label fw-bold">Plat Kendaraan :</label>
                                            <input type="text" name="plat_kendaraan" id="edit-plat_kendaraan" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="jenis_armada" class="form-label fw-bold">Jenis Armada :</label>
                                            <select name="jenis_armada" id="edit-jenis_armada" class="form-select" required>
                                                <option value="Vendor">Vendor</option>
                                                <option value="Jalur">Jalur</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="waktu_berangkat" class="form-label fw-bold">Waktu Berangkat :</label>
                                            <input type="datetime-local" name="waktu_berangkat" id="edit-waktu_berangkat" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="foto_kendaraan" class="form-label fw-bold">Foto Kendaraan :</label>
                                            <img id="preview-foto_kendaraan" class="img-fluid rounded mb-2" alt="Preview Foto" style="max-height: 200px;">
                                            <button type="button" id="btn-cancel-upload" class="btn btn-sm btn-success me-2">Batal Ganti Foto</button>
                                            <input type="file" name="foto_kendaraan" id="edit-foto_kendaraan" class="form-control me-2">
                                        </div>
                                        <input type="hidden" name="old_foto_kendaraan" id="old_foto_kendaraan">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn bg-cantik text-white button-cantik">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Tutup Counter -->
                <div class="modal fade" id="modalTutupCounter" tabindex="-1" aria-labelledby="modalTutupCounterLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <form action="<?= BASE_URL; ?>/counter/tutup" id="formTutupCounter" method="POST">
                                <div class="modal-header bg-cantik text-white">
                                    <h5 class="modal-title" id="modalTutupCounterLabel">TUTUP AGEN & KP</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                    <input type="hidden" name="id_counter" id="tutup-idCounter">
                                    <div class="row flex-column">
                                        <div class="col-12 mb-3">
                                            <label for="counter" class="form-label fw-bold">Nama Counter</label>
                                            <input class="form-control fw-bold" type="text" name="counter" id="tutup-counter" style="background-color: rgba(145, 53, 220, 0.3);" readonly>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="cabang" class="form-label fw-bold">Cabang</label>
                                            <input class="form-control fw-bold" type="text" name="cabang" id="tutup-cabang" style="background-color: rgba(145, 53, 220, 0.3);" readonly>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="cust_id" class="form-label fw-bold">Cust ID</label>
                                            <input class="form-control fw-bold" type="text" name="cust_id" id="tutup-cust_id" style="background-color: rgba(145, 53, 220, 0.3);" readonly>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tgltutup" class="form-label fw-bold">Tgl Tutup</label>
                                            <input class="form-control" type="date" name="tgltutup" id="tutup-tgl" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kettutup" class="form-label fw-bold">Keterangan Tutup</label>
                                            <input class="form-control" type="text" name="kettutup" id="tutup-ket" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn bg-cantik button-cantik text-white">SUBMIT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Tambah User -->
                <div class="modal fade" id="modalCreateUser" tabindex="-1" aria-labelledby="modalCreateUserLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <form action="<?= BASE_URL; ?>/user_hybrid/create" id="formCreateUser" method="POST">
                                <div class="modal-header bg-cantik text-white">
                                    <h5 class="modal-title" id="modalCreateUserLabel">Form Create User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                    <input type="hidden" name="id_counter" id="create-idCounter">
                                    <div class="row flex-column">
                                        <div class="col-12 mb-3">
                                            <label for="nama_counter" class="form-label fw-bold">Nama Counter</label>
                                            <input class="form-control fw-bold" type="text" name="nama_counter" id="create-nama_counter" style="background-color: rgba(145, 53, 220, 0.3);" readonly>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="cust_id" class="form-label fw-bold">Cust ID</label>
                                            <input class="form-control fw-bold" type="text" name="cust_id" id="create-cust_id" style="background-color: rgba(145, 53, 220, 0.3);" readonly>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="username" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input class="form-control fw-bold" type="text" name="username" id="create-username" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="user_id" class="form-label fw-bold">User ID <span class="text-danger">*</span></label>
                                            <input class="form-control fw-bold" type="text" name="user_id" id="create-user_id" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="password" id="create-password" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="nik" class="form-label fw-bold">NIK <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="nik" id="create-nik" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="status" class="form-label fw-bold">Status</label>
                                            <select class="form-select" name="status" id="create-status" required>
                                                <option value="AGEN">AGEN</option>
                                                <option value="GERAI">GERAI</option>
                                                <option value="CABANG UTAMA">CABANG UTAMA</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn bg-cantik button-cantik text-white">SUBMIT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</main>