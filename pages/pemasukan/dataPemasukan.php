<div class="content-wrapper">
<div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h4 class="card-title">Data Pemasukan</h4>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalPemasukan">Tambahkan pemasukan</button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="modalPemasukan" tabindex="-1" role="dialog" aria-labelledby="modalPemasukanLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPemasukanLabel">Tambahkan Pemasukan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="pemasukan/proses/prosesTambahkanPemasukan.php" method="post">
                            <div class="modal-body">
                                    <p>Nama</p>
                                    <input type="text"class="form-control" name="nama" placeholder="masukkan nama pemasukan.."><br>
                                    <p>Jumlah</p>
                                <input type="number" class="form-control" name="jumlah" placeholder="masukkan jumlah.."><br>
                                <p>Nominal</p>
                                <input type="number" class="form-control" name="nominal" placeholder="masukkan nominal.."><br>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary fw-bold" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Tambahkan</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    <!-- end modal -->


                  <div class="table-responsive mt-4">
                  <?php
                        include_once('../database/connection.php');
                        $sql = "SELECT * FROM pemasukan where user = '". $_SESSION['idUser']."' order by created_at desc";
                        $i = 1;
                        if ($hasilQuery = mysqli_query($db, $sql)) {
                            if (mysqli_num_rows($hasilQuery) > 0) {
                                ?>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama</th>
                          <th>Jumlah</th>
                          <th>Nominal</th>
                          <th>Total</th>
                          <th class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php

                                while ($kolom = mysqli_fetch_array($hasilQuery)) {
                        ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $kolom['nama']?></td>
                                    <td><?php echo $kolom['jumlah']?></td>
                                    <td><?php echo $kolom['nominal']?></td>
                                    <td><?php echo $kolom['total']?></td>
                                    <td>
                                    <div class="d-flex flex-row" style="gap:0.5em">
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalUpdate<?php echo $kolom['idPemasukan']?>">Edit</button>
                                        <a href="pemasukan/proses/prosesDeletePemasukan.php?id=<?php echo $kolom['idPemasukan'] ?>" class="btn btn-danger">Hapus</a></td>
                                    </div>    
                                </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="modalUpdate<?php echo $kolom['idPemasukan']?>" tabindex="-1" role="dialog" aria-labelledby="modalUpdateLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalUpdateLabel">Edit Pemasukan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="pemasukan/proses/prosesUpdatePemasukan.php" method="post">
                            <div class="modal-body">
                                    <p>Nama</p>
                                    <input type="text"class="form-control" name="nama" placeholder="masukkan nama pemasukan.." value="<?php echo $kolom['nama']?>"><br>
                                    <p>Jumlah</p>
                                <input type="number" class="form-control" name="jumlah" placeholder="masukkan jumlah.." value="<?php echo $kolom['jumlah']?>"><br>
                                <p>Nominal</p>
                                <input type="number" class="form-control" name="nominal" placeholder="masukkan nominal.." value="<?php echo $kolom['nominal']?>"><br>
                                <input type="text" name="idPemasukan" value="<?php echo $kolom['idPemasukan']?>" hidden>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary fw-bold" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    <!-- end modal -->
                        <?php
                                }
                            }else {
                                echo "<p>Saat ini tidak ada pemasukan</p>";
                            }
                        }
?>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

</div>