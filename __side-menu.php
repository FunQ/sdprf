<script>
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
</script>
<div id="layoutSidenav_nav">
	<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
		<div class="sb-sidenav-menu">
			<div class="nav">
				<div class="sb-sidenav-menu-heading">Menu</div>
				<!-- Jika Hak Akses Admin maka menu barang masuk muncul -->
				<?php if($_SESSION['log']['akses'] == 'Admin'){ ?>
					<a class="nav-link" href="index"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Menampilkan semua stok barang">
						<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>STOK BARANG
					</a>
					<a class="nav-link" href="masuk"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Input semua barang yang masuk">
						<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>BARANG MASUK
					</a>
					<a class="nav-link" href="permintaan"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Permintaan barang semua user/guru">
						<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>PERMOHONAN BARANG
					</a>
					<a class="nav-link" href="keluar"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Pengambilan barang untuk semua user/guru">
						<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>PENGAMBILAN BARANG
					</a>
					<a class="nav-link" href="keluarku"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Daftar barang yang pernah saya ambil">
						<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>RIWAYAT PENGAMBILANKU
					</a>
				<div class="sb-sidenav-menu-heading">Laporan</div>
					<a class="nav-link" href="rep-stock"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Laporan Stok Barang">
						<div class="sb-nav-link-icon"><i class="fas fa-cloud"></i></div>STOK BARANG
					</a>
					<a class="nav-link" href="rep-in-out"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Laporan Keluar/Masuk Barang">
						<div class="sb-nav-link-icon"><i class="fas fa-cloud"></i></div>KELUAR/MASUK BARANG
					</a>
					<a class="nav-link" href="rep-request"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Laporan Permohonan Barang">
						<div class="sb-nav-link-icon"><i class="fas fa-cloud"></i></div>PERMOHONAN BARANG
					</a>
				<div class="sb-sidenav-menu-heading">Administrasi</div>
					<a class="nav-link" href="user"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Tambah dan hapus akun">
						<div class="sb-nav-link-icon"><i class="fas fa-coffee"></i></div>PENGELOLAAN AKUN
					</a>
				<?php }else{ ?>
					<a class="nav-link" href="keluar-user"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Menu untuk mengambil barang yang tersedia">
						<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>PENGAMBILAN BARANG
					</a>
					<a class="nav-link" href="permintaan-user"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Menu untuk mengajukan permohonan barang">
						<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>PERMOHONAN BARANG
					</a>
					<a class="nav-link" href="keluarku"
						data-toggle="tooltip" data-html="true" data-placement="top" title="Menampilkan apa yang pernah saya ambil">
						<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>RIWAYAT PENGAMBILANKU
					</a>
				<?php }?>
			</div>
		</div>
	</nav>
</div>