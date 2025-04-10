<?php
    require_once "model/__connection.php";
    require_once "model/UserModel.php";
    if(!isset($_SESSION['res'])) header('location:login');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
        <title>Reset Kata Sandi</title>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header" style="background-color:#0496ad;color:white">
                                        <h3 class="text-center my-4">Pemulihan Kata Sandi</h3>
                                    </div>
                                    <div class="card-body" style="padding-top:10px !important; padding-bottom:5px !important;">
                                        <div id="errMessage" class='alert alert-danger alert-dismissible fade mt-0 mb-1' role='alert'>
                                            <strong>Kata sandi baru</strong> dan <strong>Konfirmasi kata sandi</strong> harus sama.
                                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <form method="post">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <input type="hidden" name="id_user" value="<?=(isset($_SESSION['res']))? $_SESSION['res']['id_user'] : "";?>"/>
                                                    <div class="form-group">
                                                        Kata Sandi Baru
                                                        <input class="form-control" id="password" type="password" name="password" maxlength="30" autocomplete="off" required="required" autofocus/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        Konfirmasi Kata Sandi
                                                        <input class="form-control" id="confPassword" type="password" name="k_password" maxlength="30" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="login">Kembali ke halaman login</a>
                                                <button type="submit" class="shadow btn btn-primary" name="reset_password">Reset Kata Sandi</button>
                                            </div>
                                            <br/>
                                        </form>
                                        <?php
                                            if(isset($_POST['reset_password'])){
                                                $userID	        = $_POST['id_user'];
                                                $password		= $_POST['password'];
                                                $confPassword	= $_POST['k_password'];

                                                $redirectTo	= 'login';
                                                $qry	= $mysqli->query("SELECT * FROM user WHERE id_user='$userID'");
                                                $numRows= $qry->num_rows;
                                                if($numRows > 0){
                                                    if($password == $confPassword){
                                                        $mysqli->autocommit(FALSE);
                                                        $stmt = $mysqli->prepare("UPDATE user SET waktu=$timezone, password=? WHERE id_user=?");
                                                        $stmt->bind_param('si', $password, $userID);
                                                        $stmt->execute();
                                                        $stmt->close();
                                                        $commit = $mysqli->commit();
                                                        if($commit){
                                                            session_destroy();
                                                            showMessage('Penggantian kata sandi berhasil.',$redirectTo);
                                                        }else{
                                                            showMessage('Penggantian kata sandi gagal.','reset-password');
                                                        }
                                                    }else{
                                                        echo '<script>
                                                            var element = document.getElementById("errMessage");
                                                            element.classList.add("show");
                                                            if ( window.history.replaceState ) {
                                                                window.history.replaceState( null, null, window.location.href );
                                                            }
                                                        </script>';
                                                    }
                                                }else{
                                                    showMessage('Akun anda belum terdaftar.',$redirectTo);
                                                }
                                            }
                                            ?>
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