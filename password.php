<?php
    ob_start();
    require_once "model/__connection.php";
    require_once "__clear-session.php";
?>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
        <title>Pemulihan Kata Sandi - <?=$appName?></title>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header" style="background-color:#0496ad;color:white">
                                        <h3 class="text-center my-4">Pemulihan Kata Sandi</h3>
                                    </div>
                                    <div class="card-body" style="padding-bottom: 0px !important;">
                                        <form method="post">
                                            <div class="form-group">
                                                <label class="small mb-1">Email</label>
                                                <input class="form-control py-4" id="inputEmail" name="email" type="email"
                                                    aria-describedby="emailHelp" placeholder="Masukkan alamat email" required/>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="login">Kembali ke halaman login</a>
                                                <button class="shadow btn btn-primary" name="resetpw">Reset Kata Sandi</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="errMessage" class='alert alert-danger alert-dismissible fade mt-0' role='alert'>
                                        Email yang anda masukkan tidak terdaftar.
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <?php
                                        if(isset($_POST['resetpw'])){
                                            $email  = $_POST['email'];
                                            $qry    = $mysqli->query("SELECT * FROM user WHERE email='$email'");
                                            $numRows= $qry->num_rows;

                                            if($numRows == 1){
                                                $data = $qry->fetch_assoc();
                                                $_SESSION['res'] = array(
                                                    'id_user'   => $data['id_user'],
                                                    'username'  => $data['username'],
                                                    'nama'      => $data['nama'],
                                                    'akses'     => $data['akses']
                                                );
                                                header('location:reset-password');
                                            }else{
                                                echo '<script>
                                                    var element = document.getElementById("errMessage");
                                                    element.classList.add("show");
                                                    if ( window.history.replaceState ) {
                                                        window.history.replaceState( null, null, window.location.href );
                                                    }
                                                </script>';
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                
            </div>
        </div>
        <?php include_once "__form-script.php";?>
    </body>
    <script>
        $(function(){
            $("#inputEmail").focus();
        });
    </script>
</html>