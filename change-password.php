<?php
	require_once "model/UserModel.php";
	require_once "__cek-status.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
        <title>Aplikasi Stok Barang - Akun</title>
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
                                        <h3 class="text-center font-weight-light my-4">Ganti Kata Sandi</h3>
                                    </div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-row">
                                                <input type="hidden" name="id_user" value="<?=$_SESSION['log']['id_user'];?>" />
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        Kata Sandi Lama
                                                        <input class="form-control" type="password" name="old_password" id="oldPass" autocomplete="off" maxlength="30" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        Kata Sandi Baru
                                                        <input class="form-control" type="password" name="password" maxlength="30" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        Konfirmasi Kata Sandi
                                                        <input class="form-control" type="password" name="k_password" autocomplete="off" maxlength="30" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <br />
                                            <button type="submit" class="shadow btn btn-primary btn-block" name="changePassword">
                                                Ganti Kata Sandi
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            <div id="layoutAuthentication_footer">
                
            </div>
        </div>
        <?php include_once "__form-script.php";?>
    </div>
    </body>
    <script>
        $(function(){
            $("#oldPass").focus();
        });
    </script>
</html>
