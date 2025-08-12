$(document).ready(function () {
  // ========================================
  // 1. INISIALISASI DATATABLES
  // ========================================
  let table1 = $('#example').DataTable({
    scrollX: true,
    scrollCollapse: true,
    paging: true,
    initComplete: function () {
      if (window.location.pathname.includes('/armada_masuk')) {
        setTimeout(() => {
          Swal.close();
        }, 400);
      }
    }
  });
  table1.columns.adjust().draw();

  let table2 = $('#example2').DataTable({
    scrollX: true,
    scrollCollapse: true,
    paging: true,
    initComplete: function () {
      if (window.location.pathname.includes('/armada_masuk')) {
        setTimeout(() => {
          Swal.close();
        }, 400);
      }
    }
  });
  table2.columns.adjust().draw();

  // ========================================
  // 2. INISIALISASI SELECT2
  // ========================================
  $('.select2').select2({
        placeholder: 'Pilih Cabang',
        allowClear: true,
        width: '100%', // penting agar lebar penuh
         dropdownParent: $('#modalTambahUser')
    });


  // ========================================
  // 3. MODAL TAMBAH ARMADA
  // ========================================
  function getLocalDateTimeString() {
      const now = new Date();
      const offset = now.getTimezoneOffset(); // dalam menit
      const localTime = new Date(now.getTime() - offset * 60000);
      return localTime.toISOString().slice(0, 16);
  }

  $('#modalTambahArmada').on('shown.bs.modal', function () {
    const inputMasuk = document.getElementById('tambah-waktu_masuk');
    const inputBerangkat = document.getElementById('tambah-waktu_berangkat');
    const inputSelesai = document.getElementById('tambah-waktu_selesai'); // misalnya input ketiga

    if (inputMasuk) inputMasuk.value = getLocalDateTimeString();
    if (inputBerangkat) inputBerangkat.value = getLocalDateTimeString();
    if (inputSelesai) inputSelesai.value = getLocalDateTimeString();
  });

  $(document).on('submit', '#formTambahArmada', function (e) {
  e.preventDefault();
  const formElement = this;
  const formData = new FormData(formElement); // FormData bisa menangani file

    $.ajax({
      url: BASE_URL + '/armada_masuk/tambah',
      method: 'POST',
      data: formData,
      processData: false, // Jangan proses data (biar FormData tetap utuh)
      contentType: false, // Jangan set Content-Type manual
      success: function (response) {
        try {
          const res = typeof response === 'string' ? JSON.parse(response) : response;
          if (res.status === 'success') {
            $('#modalTambahArmada').modal('hide');
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: res.message
            }).then(() => location.reload());
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: res.message || 'Gagal menyimpan data.'
            });
          }
        } catch (err) {
          console.error('Respon tidak valid JSON:', err);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Respon dari server tidak dapat dibaca.'
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Server Error',
          text: 'Terjadi kesalahan saat mengirim data.'
        });
      }
    });
  });

  // ========================================
  // 4. MODAL PREVIEW POTO
  // ========================================
  $(document).on('click', '.lihat-foto-btn', function () {
  const armadaId = $(this).data('id');

    $.ajax({
      url: BASE_URL + '/armada_masuk/getFoto',
      type: 'POST',
      data: { id: armadaId },
      success: function (res) {
        // Misalnya responnya berupa <img src="..." />
        $('#fotoKendaraanContent').html(res);
        $('#modalFotoKendaraan').modal('show');
      },
      error: function () {
        $('#fotoKendaraanContent').html('<p class="text-danger">Gagal memuat foto.</p>');
        $('#modalFotoKendaraan').modal('show');
      }
    });
  });

  // Foto report
   $(document).on('click', '.lihat-foto-btn', function () {
  const armadaId = $(this).data('id');

  $.ajax({
    url: BASE_URL + '/report/getFoto',
    type: 'POST',
    data: { id: armadaId },
    success: function (res) {
      $('#fotoKendaraanContent').html(res);
      $('#modalFotoKendaraan').modal('show');
    },
    error: function () {
      $('#fotoKendaraanContent').html('<p class="text-danger">Gagal memuat foto.</p>');
      $('#modalFotoKendaraan').modal('show');
    }
  });
});

  // ========================================
  // 5. MODAL EDIT ARMADA MASUK
  // ========================================
  $(document).on('click', '.btn-editArmadaMasuk', function () {
    const id = $(this).data('id');
    $.ajax({
      url: BASE_URL + '/armada_masuk/getArmadaMasukById',
      method: 'POST',
      data: { id_armada : id },
      dataType: 'json',
      success: function (data) {
        $('#edit-id_armada').val(data.id_armada);
        $('#edit-nama_petugas').val(data.nama_petugas);
        $('#edit-waktu_masuk').val(data.waktu_masuk);
        $('#edit-tonnase').val(data.tonnase);
        $('#edit-nama_driver').val(data.nama_driver);
        $('#edit-plat_kendaraan').val(data.plat_kendaraan);
        $('#edit-jenis_armada').val(data.jenis_armada);
        $('#edit-waktu_berangkat').val(data.waktu_berangkat);
        const fotoUrl = BASE_URL + '/' + data.foto_kendaraan;
        $('#preview-foto_kendaraan').attr('src', fotoUrl);
        $('#old_foto_kendaraan').val(data.foto_kendaraan);
        $('#edit-status').val(data.status);
        const modal = new bootstrap.Modal(document.getElementById('modalEditArmadaMasuk'));
        modal.show();
        
        // Setelah modal tampil, binding event onchange
        $('#edit-foto_kendaraan').on('change', function () {
          const file = this.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
              $('#preview-foto_kendaraan').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
          }
        });

        document.getElementById('btn-cancel-upload').addEventListener('click', function () {
        const fileInput = document.getElementById('edit-foto_kendaraan');
        const preview   = document.getElementById('preview-foto_kendaraan');
        const oldFoto   = document.getElementById('old_foto_kendaraan').value;
            // Kosongkan input
            fileInput.value = "";
            // Tampilkan ulang foto lama
            preview.src = BASE_URL + '/' + oldFoto;
            // Opsional info ke user
            alert('Perubahan foto dibatalkan.');
        });
      },
      error: function (xhr, status, error) {
        console.error("Gagal ambil data:", error);
        console.log("Respon server:", xhr.responseText); // ⬅️ ini bantu lihat error PHP
      }
    });
  });
  // ========================================
  $(document).on('submit', '#formEditArmadaMasuk', function (e) {
  e.preventDefault();
  const form = document.getElementById('formEditArmadaMasuk');
  const formData = new FormData(form); // ⬅️ tangani file!

    $.ajax({
      url: BASE_URL + '/armada_masuk/edit',
      method: 'POST',
      data: formData,
      processData: false, // ⬅️ harus false untuk FormData
      contentType: false, // ⬅️ jangan ubah content-type
      success: function () {
        $('#modalEditArmadaMasuk').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Data Armada Masuk berhasil diperbarui!'
        }).then(() => location.reload());
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'Terjadi kesalahan saat mengupdate data.'
        });
      }
    });
  });


  // ========================================
  // 6. MODAL BONGKAR MUAT
  // ========================================
  $(document).on('click', '.btn-bongkarMuat', function () {
    const id = $(this).data('id');
    $.ajax({
      url: BASE_URL + '/bongkar_muat/getArmadaMasukById',
      method: 'POST',
      data: { id_armada : id },
      dataType: 'json',
      success: function (data) {
        $('#edit-id_armada').val(data.id_armada);
        $('#edit-waktu_masuk').val(data.waktu_masuk);
        $('#edit-tonnase').val(data.tonnase);
        $('#edit-nama_driver').val(data.nama_driver);
        $('#edit-plat_kendaraan').val(data.plat_kendaraan);
        $('#edit-jenis_armada').val(data.jenis_armada);

        const modal = new bootstrap.Modal(document.getElementById('modalBongkarMuat'));
        modal.show();

      },
      error: function (xhr, status, error) {
        console.error("Gagal ambil data:", error);
        console.log("Respon server:", xhr.responseText); // ⬅️ ini bantu lihat error PHP
      }
    });
  });
  // ========================================
  $(document).on('submit', '#formBongkarMuat', function (e) {
  e.preventDefault();
  const form = document.getElementById('formBongkarMuat');
  const formData = new FormData(form); // ⬅️ tangani file!

    $.ajax({
      url: BASE_URL + '/bongkar_muat/bongkarMuat',
      method: 'POST',
      data: formData,
      processData: false, // ⬅️ harus false untuk FormData
      contentType: false, // ⬅️ jangan ubah content-type
      success: function () {
        $('#modalBongkarMuat').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Barang akan di bongkar, Jika selesai bongkar silahkan klik tombol selesai bongkar.'
        }).then(() => location.reload());
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'Terjadi kesalahan saat mengupdate data.'
        });
      }
    });
  });

// ========================================
  // 7. MODAL SELESAI MUAT
  // ========================================
  $(document).on('click', '.btn-prosesDone', function () {
    const id = $(this).data('id');
    $.ajax({
      url: BASE_URL + '/bongkar_muat/getArmadaMasukById',
      method: 'POST',
      data: { id_armada : id },
      dataType: 'json',
      success: function (data) {
        $('#proses-id_armada').val(data.id_armada);
        $('#proses-plat_kendaraan').val(data.plat_kendaraan);

        const modal = new bootstrap.Modal(document.getElementById('modalProsesDone'));
        modal.show();

      },
      error: function (xhr, status, error) {
        console.error("Gagal ambil data:", error);
        console.log("Respon server:", xhr.responseText); // ⬅️ ini bantu lihat error PHP
      }
    });
  });
  // ========================================
  $(document).on('submit', '#formProsesDone', function (e) {
  e.preventDefault();
  const form = document.getElementById('formProsesDone');
  const formData = new FormData(form); // ⬅️ tangani file!

    $.ajax({
      url: BASE_URL + '/bongkar_muat/Done',
      method: 'POST',
      data: formData,
      processData: false, // ⬅️ harus false untuk FormData
      contentType: false, // ⬅️ jangan ubah content-type
      success: function () {
        $('#modalProsesDone').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Barang selesai bongkar.'
        }).then(() => location.reload());
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'Terjadi kesalahan saat mengupdate data.'
        });
      }
    });
  });


// 9. range filter tanggal report
    function validateDates() {
    const start = new Date($('#startDate').val());
    const end = new Date($('#endDate').val());

    const valid =
      !isNaN(start) &&
      !isNaN(end) &&
      end >= start &&
      start.getMonth() === end.getMonth() &&
      start.getFullYear() === end.getFullYear();

    $('#submitBtn').prop('disabled', !valid);
    return valid;
  }

  // 2. Event saat tanggal berubah
  $('#startDate, #endDate').on('change', validateDates);

  // 3. Submit form
  $('#filterForm').on('submit', function (e) {
    e.preventDefault();
    if (!validateDates()) return;

    const start = $('#startDate').val();
    const end = $('#endDate').val();

    $.ajax({
      url: BASE_URL + '/armada_masuk/filterReport',
      type: 'POST',
      data: { startDate: start, endDate: end },
      success: function (res) {
        $('#reportTableBody').html(res);
        $('#downloadReportBtn').show();
      },
      error: function () {
        alert('Gagal mengambil data.');
      }
    });
  });

  // 4. Tombol download
  $('#downloadReportBtn').on('click', function () {
    const start = $('#startDate').val();
    const end = $('#endDate').val();
    window.location.href = BASE_URL + '/report/downloadReport?start=' + start + '&end=' + end;
  });



});