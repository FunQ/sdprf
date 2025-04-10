<?php
	require_once "model/ClassesModel.php";
	require_once 'model/UserModel.php';
	if(!isset($_SESSION['log'])) header('location:login');
	if($_SESSION['log']['akses'] == 'User') header("location:index");
	
	$email		= "";
	$username	= "";
	$nama		= "";
	$gender		= "";
	$aksesUser	= "";
	if(isset($_SESSION['acc'])){
		$email		= $_SESSION['acc']['email'];
		$username	= $_SESSION['acc']['username'];
		$nama		= $_SESSION['acc']['nama'];
		$gender		= $_SESSION['acc']['gender'];
		$aksesUser	= $_SESSION['acc']['akses'];
	}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<?php include_once "__header.php";?>
        <?php require_once "__load-user.php";?>
		<title>Kelola Akun - <?=$appName?></title>
    </head>
    <body class="sb-nav-fixed sb-sidenav-toggled">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h2 class="mt-4">Kelola Akun</h2>
						<div class="card-header p-1">
							<!-- Button to Open the Modal -->
							<button type="button" class="shadow btn btn-primary" data-toggle="modal" data-target="#formTambah">
								Tambah<br/>Akun Baru
							</button>
							<button type="button" class="shadow btn btn-secondary ml-1" id="btnEdit" data-toggle="modal" data-target="#formEdit" disabled>
								Edit<br/>Akun
							</button>
							<button type="button" class="shadow btn btn-secondary ml-1" id="btnHapus" data-toggle="modal" data-target="#formHapus" disabled>
								Hapus<br/>Akun
							</button>
						</div>
						<div class="card-body p-1">
							<div class="table-responsive">
								<table id="tableUser" width="70%" cellspacing="0"
									class="table table-bordered table-striped table-hover table-sm cell-border">
									<thead style="text-align:center">
										<tr>
											<th>ID</th>
											<th>Nama</th>
											<th>Akun</th>
											<th>Email</th>
											<th>Hak Akses</th>
											<th>Gender</th>
											<th>Waktu</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
                    </div>
                </main>
            </div>
        </div>
		<?php include_once "__script.php";?>
		<script src="js/script-users-management.js"></script>
    </body>
	<!-- Modal untuk Tambah User Baru -->
	<div class="modal fade" id="formTambah">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h5 class="modal-title">Tambah Akun Baru</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<form method="post">
						Nama Lengkap
						<input type="text" class="form-control" name="nama" id="namaUser" maxlength="80" value="<?=$nama;?>"><br />
						<div class="form-row">
							<div class="col-md-6">
								Akun
								<input type="text" class="form-control" name="username" id="username" maxlength="30" value="<?=$username;?>" required><br />
							</div>
							<div class="col-md-6">
								Jenis Kelamin
								<select name="gender" class="form-control">
									<option value="l" <?=($gender == "l") ? "selected" : "";?>>Laki-Laki</option>
									<option value="p" <?=($gender == "p") ? "selected" : "";?>>Perempuan</option>
								</select><br />
							</div>
							<div class="col-md-6">
								Hak Akses</b><br />
								<select name="listAkses" class="form-control">
									<option value="User" <?=($aksesUser == "User") ? "selected" : "";?>>User</option>
									<option value="Admin" <?=($aksesUser == "Admin") ? "selected" : "";?>>Admin</option>
								</select><br />
							</div>
							<div class="ml-1 mr-1" style="width:100%">
								Email
								<input type="email" class="form-control" name="email" id="email" maxlength="30" value="<?=$email;?>" required><br />
							</div>
							<div class="col-md-6">
								Kata Sandi
								<input type="password" class="form-control" id="inputPassword" name="password" autocomplete="off" maxlength="30" required><br />
							</div>
							<div class="col-md-6">
								Konfirmasi Kata Sandi
								<input type="password" class="form-control" id="inputConfirmPassword" name="konfirmasiPassword" autocomplete="off" maxlength="30" required><br />
							</div>
						</div>
						<button type="submit" class="shadow btn btn-primary" name="addAccount">Tambahkan Akun</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal untuk Edit User -->
	<div class="modal fade" id="formEdit">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h5 class="modal-title">Edit Akun</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<form method="post">
						<input type="hidden" name="id_user" id="idUserEdit">
						Nama Lengkap<br />
						<input type="text" class="form-control" name="nama" id="namaUserEdit"  maxlength="80" required><br />
						<div class="form-row">
							<div class="col-md-6" style="min-width:70%">
								Akun<br />
								<input type="text" class="form-control" name="username" id="usernameEdit"  maxlength="30" required><br />
							</div>
							<div class="col-md-6" style="max-width:30%">
								Jenis Kelamin
								<select name="genderEdit" id="genderEdit" class="form-control">
									<option value="l">Laki-Laki</option>
									<option value="p">Perempuan</option>
								</select><br />
							</div>
							<div class="col-md-6" style="min-width:70%">
								Email
								<input type="text" class="form-control" name="email" id="emailEdit" maxlength="30" required><br />
							</div>
							<div class="col-md-6" style="max-width:30%">
								Hak Akses<br />
								<select name="listAkses" id="listAksesEdit" class="form-control" style="width:130px">
									<option value="Admin">Admin</option>
									<option value="User">User</option>
								</select><br />
							</div>
							<div class="col-md-6">
								Kata Sandi<br />
								<input type="password" class="form-control" name="password" autocomplete="off" maxlength="30"><br />
							</div>
							<div class="col-md-6">
								Konfirmasi Kata Sandi <br />
								<input type="password" class="form-control" name="konfirmasiPassword" autocomplete="off" maxlength="30"><br />
							</div>
							<button type="submit" class="btn btn-primary btn-block" name="editAccount">Perbarui</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal untuk Delete User -->
	<div class="modal fade" id="formHapus">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h5 class="modal-title">Hapus Akun</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<form method="post">
					<div class="modal-body">
						<input type="hidden" name="id_user" id="idUserDelete">
						<p>Apakah anda yakin ingin menghapus <strong><label id="namaUserDelete"></label></strong> ( <strong><label id="aksesUserDelete"></label></strong> ) ?</p>
						<button type="submit" class="btn btn-danger btn-block" name="deteleAccount">Hapus Pengguna</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php if(isset($_SESSION['acc'])) echo "<script>$('#formTambah').modal('show');</script>";?>
</html>