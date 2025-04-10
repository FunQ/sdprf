<?php
    ob_start();
    require_once "__clear-session.php";
    require_once "model/__connection.php";
    if(isset($_SESSION['log'])) header('location:index');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
        <title>Login</title>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5" style="max-width:365px; max-height:415px">
                                    <div class="card-header" style="background-color:#0496ad;color:white;">
                                        <div class="col-lg-14" style="text-align:center">
                                            <img src="assets/img/logo.png" width="80" height="80">
                                            <h5 class="mt-3">
                                                <?=strtoupper($appName);?>
                                            </h5>
                                            <h6 class="mb-3"><?=$lembaga;?></h6>
                                        </div>
                                    </div>
                                    <div class="card-body" style="margin-bottom:170px">
                                        <form method="post">
                                            <div class="form-group">
                                                <label class="small mb-1">Nama Pengguna</label>
                                                <input class="form-control py-3" name="username" id="username" type="text" placeholder="Masukkan nama pengguna" required="required" autofocus>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1">Kata Sandi</label>
                                                <input class="form-control py-3" name="password" id="password" type="password" autocomplete="off" placeholder="Masukkan kata sandi" required>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mb-0">
                                                <a class="small" href="password">Lupa Kata Sandi ?</a>
                                                <button type="submit" class="shadow btn btn-primary" id="btnLogin" name="login">Masuk</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="errMessage" class='alert alert-danger alert-dismissible mt-0 fade in' role='alert'>
                                        Nama pengguna atau kata sandi salah.
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <?php
                                        if(isset($_POST['login'])){
                                            $username	= $_POST['username'];
                                            $password	= $_POST['password'];
                                            
                                            $qry = "SELECT * FROM user 
                                                WHERE username=?
                                                AND password=?";

                                            $mysqli->autocommit(FALSE);
                                            $stmt = $mysqli->prepare($qry);
                                            $stmt->bind_param('ss', $username, $password);
                                            $stmt->execute();

                                            $result = $stmt->get_result();
                                            if($result->num_rows == 1){
                                                $data   = $result->fetch_assoc();
                                                $_SESSION['log'] = array(
                                                    'id_user'   => $data['id_user'],
                                                    'username'  => $data['username'],
                                                    'nama'      => $data['nama'],
                                                    'akses'     => $data['akses'],
                                                    'gender'    => $data['gender']
                                                );
                                                $stmt->free_result();
                                                header('location:index');
                                            }else{
                                                echo '
                                                <script>
                                                    var element = document.getElementById("errMessage");
                                                    element.classList.add("show");
                                                    if ( window.history.replaceState ) {
                                                        window.history.replaceState( null, null, window.location.href );
                                                    }
                                                    $("#username").focus();
                                                </script>';
                                            }
                                            $mysqli->close();
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <?php include_once "__form-script.php";?>
    </body>
</html>