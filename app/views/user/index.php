   <main>
       <div class="container-fluid px-4">
           <h5 class="mt-4 fw-bold" style="border-bottom: 1px solid black;">Data User</h5>
           <?php Flasher::flash();  ?>
           <div class="row">
               <div class="col-lg-6">
                   <?php Flasher::loginFlash(); ?>
               </div>
           </div>
           <div class="card mb-4 mt-4">
               <div class="container mt-2">
                   <button
                       class="btn text-white bg-cantik button-cantik btn-tambahUser"
                       data-bs-toggle="modal"
                       data-bs-target="#modalTambahUser"
                       data-keterangan="<?= $open['keterangan']; ?>">
                       <i class="fa fa-plus"></i> Tambah User
                   </button>
               </div>
               <div class="card-header mt-4">
                   <i class="fas fa-table me-1"></i>
                   Data User Aplikasi
               </div>
               <div class="card-body">
                   <table id="example" class="display" style="width:100%">
                       <thead>
                           <tr class="bg-ungu text-white">
                               <th class="small text-center">NO</th>
                               <th class="small text-center">USERNAME</th>
                               <th class="small text-center">NAMA AGEN</th>
                               <th class="small text-center">CABANG</th>
                               <th class="small text-center">ROLE</th>
                               <th class="small text-center">STATUS</th>
                               <th class="small text-center">ACTION</th>

                           </tr>
                       </thead>
                       <tbody>
                           <?php
                            $no = 1;
                            foreach ($data['user'] as $user) :
                            ?>
                               <tr>
                                   <td class="small text-center"><?= $no++ ?></td>
                                   <td class="small text-center"><?= $user['username']; ?></td>
                                   <td class="small text-center"><?= $user['name']; ?></td>
                                   <td class="small text-center"><?= $user['cabang']; ?></td>
                                   <td class="small text-center"><?= $user['role']; ?></td>
                                   <td class="small text-center"><?= $user['status']; ?></td>
                                   <td class="text-center">
                                       <div class="d-flex justify-content-center align-items-center gap-2">
                                           <button
                                               class="btn btn-warning btn-sm btn-editUser d-flex align-items-center gap-1"
                                               data-id="<?= $user['id']; ?>"
                                               data-user="<?= $user['username']; ?>"
                                               data-name="<?= $user['name']; ?>"
                                               data-cabang="<?= $user['cabang']; ?>"
                                               data-role="<?= $user['role']; ?>"
                                               data-status="<?= $user['status']; ?>">
                                               <i class="fa fa-edit"></i> EDIT
                                           </button>
                                           <button
                                               class="btn btn-success btn-sm btn-editPass d-flex align-items-center gap-1"
                                               data-id="<?= $user['id']; ?>"
                                               data-username="<?= $user['username']; ?>">
                                               <i class="fa fa-lock"></i> <span>PASS</span>
                                           </button>
                                       </div>
                                   </td>
                               </tr>
                           <?php endforeach; ?>
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
   </main>

   <!-- Modal Tambah -->
   <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg">
           <div class="modal-content">
               <form action="<?= BASE_URL; ?>/user/tambahUser" method="POST">
                   <div class="modal-header bg-cantik text-white">
                       <h5 class="modal-title " id="modalTambahUserLabel">Tambah Data User</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                   </div>
                   <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                       <div class="row">
                           <div class="col-md-6 mb-3">
                               <label for="username" class="form-label fw-bold">Username</label>
                               <input type="text" class="form-control" name="username" id="username" required>
                           </div>
                           <div class="col-md-6 mb-3">
                               <label for="password" class="form-label fw-bold">Password</label>
                               <input type="text" class="form-control" name="password" id="password" required>
                           </div>
                       </div>
                       <div class="row">
                           <div class="col-md-6 mb-3">
                               <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                               <input type="text" class="form-control" name="name" id="name" required>
                           </div>
                           <div class="col-md-6 mb-3">
                               <label for="cabang" class="form-label fw-bold">Cabang</label>
                               <select class="form-select select2 " name="cabang" id="cabang" required>
                                   <option value="" disabled selected>Pilih Cabang</option>
                                   <?php foreach ($data['cabang'] as $row): ?>
                                       <option value="<?= $row['nama_cabang']; ?>"><?= $row['nama_cabang']; ?></option>
                                   <?php endforeach; ?>
                               </select>
                           </div>
                       </div>

                       <div class="row">
                           <div class="col-md-6 mb-3">
                               <label for="role" class="form-label fw-bold">Role</label>
                               <select type="text" class="form-select" name="role" id="role" required>
                                   <option value="security">SECURITY</option>
                                   <option value="user">USER</option>
                                   <option value="admin">ADMIN</option>
                               </select>
                           </div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                       <button type="submit" class="btn bg-cantik button-cantik text-white">Tambah User</button>
                   </div>
               </form>
           </div>
       </div>
   </div>

   <!-- Modal Edit  User-->
   <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg">
           <div class="modal-content">
               <form action="<?= BASE_URL; ?>/user/editUser" method="POST">
                   <div class="modal-header bg-cantik text-white">
                       <h5 class="modal-title " id="modalEditUserLabel">Edit Data User</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                   </div>
                   <div class="modal-body">
                       <input type="hidden" name="edit-id" id="edit-id">
                       <div class="row">
                           <div class="col-md-6 mb-3">
                               <label for="edit-username" class="form-label fw-bold">Username</label>
                               <input type="text" class="form-control" name="edit-username" id="edit-username" required>
                           </div>
                           <div class="col-md-6 mb-3">
                               <label for="edit-name" class="form-label fw-bold">Nama</label>
                               <input type="text" class="form-control" name="edit-name" id="edit-name" required>
                           </div>
                       </div>
                       <div class="row">
                           <div class="col-md-6 mb-3">
                               <label for="cabang" class="form-label fw-bold">Cabang</label><br>
                               <select class="form-select select2 w-100" name="edit-cabang" id="cabang-edit" required>
                                   <option value="">Pilih Cabang</option>
                                   <?php foreach ($data['cabang'] as $row): ?>
                                       <option value="<?= $row['nama_cabang']; ?>"><?= $row['nama_cabang']; ?></option>
                                   <?php endforeach; ?>
                               </select>
                           </div>
                           <div class="col-md-6 mb-3">
                               <label for="edit-role" class="form-label fw-bold">Role</label>
                               <select type="text" class="form-select" name="edit-role" id="edit-role" required>
                                   <option value="security">SECURITY</option>
                                   <option value="user">USER</option>
                                   <?php if (isset($data['userRole']) && in_array($data['userRole'], ['superadmin'])) : ?>
                                       <option value="admin">ADMIN</option>
                                   <?php endif; ?>
                               </select>
                           </div>
                       </div>
                       <div class="row">
                           <div class="col-md-6 mb-3">
                               <label for="edit-status" class="form-label fw-bold">Status</label>
                               <select type="text" class="form-select" name="edit-status" id="edit-status" required>
                                   <option value="aktif">AKTIF</option>
                                   <option value="nonaktif">NONAKTIF</option>
                               </select>
                           </div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                       <button type="submit" class="btn bg-cantik button-cantik text-white">Update</button>
                   </div>
               </form>
           </div>
       </div>
   </div>

   <!-- Modal Edit Pass -->
   <div class="modal fade" id="modalEditPass" tabindex="-1" aria-labelledby="modalEditPassLabel" aria-hidden="true">
       <div class="modal-dialog">
           <div class="modal-content">
               <form action="<?= BASE_URL; ?>/user/editPass" method="POST">
                   <div class="modal-header bg-card text-white">
                       <h5 class="modal-title" id="modalEditPassLabel">Edit Password User</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                   </div>
                   <div class="modal-body">
                       <!-- Gunakan hidden input untuk ID -->
                       <input type="hidden" name="id" id="edit-id-pass">
                       <div class="mb-3">
                           <label for="usernamePass" class="form-label"><b>Username</b></label>
                           <input type="text" class="form-control" name="usernamePass" id="usernamePass" required readonly>
                       </div>
                       <div class="mb-3">
                           <label for="edit-pass" class="form-label fw-bold">Password Baru</label>
                           <input type="text" class="form-control" name="edit-pass" id="edit-pass" required placeholder="Masukkan password baru">
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                       <button type="submit" class="btn bg-card button-card text-white">Update Password</button>
                   </div>
               </form>
           </div>
       </div>
   </div>