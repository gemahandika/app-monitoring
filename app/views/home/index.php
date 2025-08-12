   <main>
       <div class="container-fluid px-4">
           <h5 class="mt-4 fw-bold" style="border-bottom: solid 1px black;">Dashboard</h5>
           <?php Flasher::flash();  ?>

           <div class="row">
               <div class="col-xl-3 col-md-6">
                   <a href="<?php echo BASE_URL . '/armada_masuk/index'; ?>" class="text-decoration-none">
                       <div class="card mb-4">
                           <div class="card-body bg-card text-white text-uppercase  text-center">Waiting</div>
                           <div class="card-footer text-dark d-flex justify-content-around align-items-center px-4">
                               <i style="opacity: 0.7;" class="fas fa-clock fa-2x"></i>
                               <h2 class="mb-0 fw-bold"><?php echo $data['masuk']['total'] ?? 0; ?></h2>
                           </div>
                       </div>
                   </a>
               </div>

               <div class="col-xl-3 col-md-6">
                   <a href="<?php echo BASE_URL . '/bongkar_muat/index'; ?>" class="text-decoration-none">
                       <div class="card mb-4">
                           <div class="card-body bg-card text-white text-uppercase  text-center">On Proses</div>
                           <div class="card-footer text-dark d-flex justify-content-around align-items-center px-4">
                               <i style="opacity: 0.7;" class="fas fa-tasks fa-2x"></i>
                               <h2 class="mb-0 fw-bold"><?php echo $data['proses']['total'] ?? 0; ?></h2>
                           </div>
                       </div>
                   </a>
               </div>
               <div class="col-xl-3 col-md-6">
                   <a href="#" class="text-decoration-none">
                       <div class="card mb-4">
                           <div class="card-body bg-card text-white text-uppercase  text-center">Selesai Hari ini</div>
                           <div class="card-footer text-dark d-flex justify-content-around align-items-center px-4">
                               <i style="opacity: 0.7;" class="fas fa-clipboard-check fa-2x"></i>
                               <h2 class="mb-0 fw-bold"><?php echo $data['selesai']['total'] ?? 0; ?></h2>
                           </div>
                       </div>
                   </a>
               </div>
               <div class="col-xl-3 col-md-6">
                   <a href="#" class="text-decoration-none">
                       <div class="card  mb-4">
                           <div class="card-body bg-card text-white text-uppercase  text-center">Total</div>
                           <div class="card-footer text-dark d-flex justify-content-around align-items-center px-4">
                               <i style="opacity: 0.7;" class="fas fa-database fa-2x"></i>
                               <h2 class="mb-0 fw-bold"><?php echo $data['total']['total'] ?? 0; ?></h2>
                           </div>
                       </div>
                   </a>
               </div>
           </div>
       </div>
       <div class="container-fluid px-4">
           <div class="row mt-4 justify-content-start">
               <div class="col-12 col-lg-12">
                   <div class="card">
                       <div class="card-body">
                           <form method="GET" class="mb-4">
                               <div class="row g-2 align-items-end">
                                   <div class="col-md-3">
                                       <label for="start" class="form-label fw-bold">Tanggal Mulai</label>
                                       <input type="date" name="start" id="start" class="form-control" value="<?= $_GET['start'] ?? date('Y-m-d', strtotime('-30 days')) ?>">
                                   </div>
                                   <div class="col-md-3">
                                       <label for="end" class="form-label fw-bold">Tanggal Selesai</label>
                                       <input type="date" name="end" id="end" class="form-control" value="<?= $_GET['end'] ?? date('Y-m-d') ?>">
                                   </div>
                                   <div class="col-md-2">
                                       <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                                   </div>
                               </div>
                           </form>
                       </div>
                   </div>
               </div>
           </div>
       </div>

       <div class="container-fluid px-4">
           <div class="row mt-4 justify-content-start">
               <div class="col-md-6"> <!-- 50% width -->
                   <div class="card">
                       <div class="card-header fw-bold">Bar Chart Armada</div>
                       <div class="card-body">
                           <canvas id="armadaChart" style="height: 400px;"></canvas>
                       </div>
                   </div>
               </div>
               <div class="col-md-6"> <!-- 50% width -->
                   <div class="card">
                       <div class="card-header fw-bold">Pie Chart Armada</div>
                       <div class="card-body">
                           <canvas id="pieChart" style="max-width: 300px; height: 300px; margin: auto;"></canvas>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </main>

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
   <script>
       const chart = <?php echo json_encode($data['chart']); ?>;
       const pieData = <?php echo json_encode($data['pie']); ?>;

       // Bar Chart
       new Chart(document.getElementById('armadaChart'), {
           type: 'bar',
           data: {
               labels: chart.labels,
               datasets: [{
                       label: 'JALUR',
                       data: chart.jalur,
                       backgroundColor: 'rgba(54, 162, 235, 0.7)'
                   },
                   {
                       label: 'VENDOR',
                       data: chart.vendor,
                       backgroundColor: 'rgba(255, 99, 132, 0.7)'
                   }
               ]
           },
           options: {
               responsive: true,
               plugins: {
                   datalabels: {
                       anchor: 'end',
                       align: 'top',
                       font: {
                           weight: 'bold'
                       },
                       formatter: Math.round
                   }
               },
               scales: {
                   y: {
                       beginAtZero: true
                   }
               }
           },
           plugins: [ChartDataLabels]
       });

       // Pie Chart
       new Chart(document.getElementById('pieChart'), {
           type: 'pie',
           data: {
               labels: ['JALUR', 'VENDOR'],
               datasets: [{
                   data: [pieData.JALUR, pieData.VENDOR],
                   backgroundColor: ['#36A2EB', '#FF6384']
               }]
           },
           options: {
               plugins: {
                   legend: {
                       position: 'bottom'
                   },
                   tooltip: {
                       callbacks: {
                           label: function(context) {
                               const total = pieData.JALUR + pieData.VENDOR;
                               const value = context.raw;
                               const percent = ((value / total) * 100).toFixed(1);
                               return `${context.label}: ${value} (${percent}%)`;
                           }
                       }
                   },
                   datalabels: {
                       color: '#fff',
                       formatter: (value, ctx) => value,
                       font: {
                           weight: 'bold',
                           size: 14
                       }
                   }
               }
           },
           plugins: [ChartDataLabels]
       });
   </script>