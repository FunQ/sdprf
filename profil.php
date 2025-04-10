<?php
	require_once 'model/UserModel.php';
	require_once 'model/__connection.php';
	require_once '__cek-status.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
        <title>Profil - <?=$appName?></title>
    </head>
    <body class="sb-nav-fixed sb-sidenav-toggled">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header" style="background-color:#0496ad;color:white">
                                        <h3 class="text-center font-weight-light my-4">Profil</h3>
                                    </div>
                                    <div class="card-body">
                                        <form method="post">
                                            <?php
                                                
                                                $id_user    = $_SESSION['log']['id_user'];
                                                $sql    = "SELECT * FROM user WHERE id_user='$id_user'";
                                                $qry    = $mysqli->query($sql);
                                                $data   = $qry->fetch_assoc();
                                                $username   = $data['username'];
                                                $nama       = $data['nama'];
                                                $akses      = $data['akses'];
                                                $email      = $data['email'];
                                            ?>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="hidden" name="id_user" value="<?=$id_user;?>" />
                                                        <input type="hidden" name="akses" value="<?=$akses;?>" />
                                                        <input type="hidden" name="konfirmasiPassword" />
                                                        <input type="hidden" name="password" />
                                                        <label class="small mb-1" for="inputFirstName">Nama Lengkap</label>
                                                        <input class="form-control py-4" id="inputFirstName" type="text" name="nama" placeholder="Nama Lengkap" value="<?=$nama;?>" required />
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputLastName">Akun</label>
                                                        <input class="form-control py-4" id="inputLastName" type="text" name="username" placeholder="Masukkan nama akun" value="<?=$username;?>" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputEmail">Email</label>
                                                        <input class="form-control py-4" id="inputEmail" type="email" name="email" placeholder="Masukkan alamat email" value="<?=$email;?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <br />
                                            <button type="submit" class="shadow btn btn-primary btn-block" name="editAccountForUser">
                                            Perbarui Akun
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
        </div>
        <?php include_once "__form-script.php";?>
    </div>
    </body>
</html>
