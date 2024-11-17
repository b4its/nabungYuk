<div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Selamat Datang <?php echo $_SESSION['username'] ?></h3>
                    <h6 class="font-weight-normal mb-0">Monitoring informasi tentang saldo, pemasukan, dan pengeluaran anda..</span></h6>
                    </div>
                    <div class="col-12 col-xl-4">

                    </div>
                </div>
                </div>
            </div>
            <div class="row">
                    <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card bg-info text-light">
                        <div class="card-body">
                        <p class="mb-4">Saldo anda</p>
                        <p class="fs-30 mb-2 font-weight-bold"><?php echo formatRupiah($cekSaldo) ?></p>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card bg-success text-light ">
                        <div class="card-body">
                        <p class="mb-4">Pemasukan</p>
                        <p class="fs-30 mb-2 font-weight-bold"><?php echo formatRupiah($pemasukanSaya) ?></p>
                        </div>
                    </div>
                    </div>
                </div>
            <div class="row">
                    <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card bg-danger text-light">
                        <div class="card-body">
                        <p class="mb-4">Pengeluaran</p>
                        <p class="fs-30 mb-2 font-weight-bold"><?php echo formatRupiah($pengeluaranSaya) ?></p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>