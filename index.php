<!DOCTYPE html>
<html lang="en">
<?php
$ip      = $_SERVER['REMOTE_ADDR']; // Dapatkan IP user
$tanggal = date("Ymd"); // Dapatkan tanggal sekarang
$waktu   = time(); // Dapatkan nilai waktu
$bln = date("m");
$thn = date("Y");
$konek = mysqli_connect('localhost', 'root', '', 'bbps6532_portal');


// Cek user yang mengakses berdasarkan IP-nya 
$s = mysqli_query($konek, "SELECT * FROM statistik WHERE ip='$ip' AND tanggal='$tanggal'");
// Kalau belum ada, simpan datanya sebagai user baru
if(mysqli_num_rows($s) == 0){
  mysqli_query($konek, "INSERT INTO statistik(ip, tanggal, hits, online) VALUES('$ip', '$tanggal', '1', '$waktu')");
}
// Kalau sudah ada, update data hits user  
else{
  mysqli_query($konek, "UPDATE statistik SET hits=hits+1, online='$waktu' WHERE ip='$ip' AND tanggal='$tanggal'");
}

$query1 = mysqli_query($konek, "SELECT ip FROM statistik WHERE tanggal='$tanggal' GROUP BY ip");
$pengunjung = mysqli_num_rows($query1);


$query2        = mysqli_query($konek, "SELECT COUNT(hits) as total FROM statistik");
$hasil2        = mysqli_fetch_array($query2);
$totpengunjung = $hasil2['total'];

$query3 = mysqli_query($konek, "SELECT SUM(hits) as jumlah FROM statistik WHERE tanggal='$tanggal' GROUP BY tanggal");
$hasil3 = mysqli_fetch_array($query3);
$hits   = $hasil3['jumlah'];

$query4  = mysqli_query($konek, "SELECT SUM(hits) as total FROM statistik");
$hasil4  = mysqli_fetch_array($query4);
$tothits = $hasil4['total'];  

$query5  = mysqli_query($konek, "SELECT count(hits) as total FROM statistik WHERE MONTH(tanggal) = '$bln' GROUP BY YEAR (tanggal)") ;
$hasil5  = mysqli_fetch_array($query5);
$totmonth = $hasil5['total'];  

$today = date('Y-m-d');       // Tanggal hari ini
$thisYear = date('Y');        // Tahun sekarang
$lastYear = $thisYear - 1;    // Tahun lalu

// Total pengunjung tahun ini (sampai hari ini)
$queryThisYear = mysqli_query($konek, "
    SELECT COUNT(ip) as total 
    FROM statistik 
    WHERE YEAR(tanggal) = '$thisYear' 
      AND tanggal <= '$today'
");
$pengunjungThisYear = mysqli_fetch_array($queryThisYear)['total'];

// Total pengunjung tahun lalu (sampai tanggal yang sama)
$queryLastYear = mysqli_query($konek, "
    SELECT COUNT(ip) as total 
    FROM statistik 
    WHERE YEAR(tanggal) = '$lastYear' 
      AND tanggal <= DATE_SUB('$today', INTERVAL 1 YEAR)
");
$pengunjungLastYear = mysqli_fetch_array($queryLastYear)['total'];



// Cek berapa pengunjung yang sedang online
$bataswaktu       = time() - 300; 
$pengunjungonline = mysqli_num_rows(mysqli_query($konek, "SELECT * FROM statistik WHERE online > '$bataswaktu'"));

// Angka total pengunjung dalam bentuk gambar
$folder = "counter"; // nama folder
$ext    = ".png";     // ekstension file gambar

// ubah digit angka menjadi enam digit
$totpengunjunggbr = sprintf("%06d", $totpengunjung);
// ganti angka teks dengan angka gambar


?> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Responsive bootstrap landing template">
    <meta name="author" content="Themesdesign">

    <link rel="shortcut icon" href="images/badan-pom.png">

    <title>SPBE | Balai Besar POM di Palangka Raya</title>


    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- tiny slider -->
    <link href="css/tiny-slider.css" rel="stylesheet">
    <link rel="stylesheet" href="css/swiper.min.css" type="text/css" />


    <!-- Materialdesign icons css -->
    <link href="css/materialdesignicons.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

</head>
<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login Akses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="loginError" class="alert alert-danger" style="display:none;"></div>
        <form id="loginForm">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
          <input type="hidden" name="redirect" id="redirect-url">
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>


<body data-bs-spy="scroll" data-bs-target="#navbar-navlist" data-bs-offset="71">

    <!--Navbar Start-->
    <nav class="navbar navbar-expand-lg fixed-top sticky nav-white" id="navbar">
        <div class="container-fluid custom-container">
            <a class="navbar-brand logo text-uppercase  nav-brand-logo" href="index">
                <img src="images/badan-pom.png" class="logo-light" alt="" height="50">
                <img src="images/badan-pom.png" class="logo-dark" alt="" height="50">
            </a>
            <a class="navbar-brand logo text-uppercase  nav-brand-logo" href="index">
                <img src="images/tolak_gratifikasi.png" class="logo-light" alt="" height="50">
                <img src="images/tolak_gratifikasi.png" class="logo-dark" alt="" height="50">
            </a>
            <a class="navbar-brand logo text-uppercase  nav-brand-logo" href="index">
                <img src="images/logo_berakhlak.png" class="logo-light" alt="" height="50">
                <img src="images/logo_berakhlak.png" class="logo-dark" alt="" height="50">
            </a>
            <a class="navbar-brand logo text-uppercase  nav-brand-logo" href="index">
                <img src="images/logo_zi.png" class="logo-light" alt="" height="50">
                <img src="images/logo_zi.png" class="logo-dark" alt="" height="50">
            </a>
            <a class="navbar-brand logo text-uppercase  nav-brand-logo" href="index">
                <img src="images/logo_kata_bpom.png" class="logo-light" alt="" height="50">
                <img src="images/logo_kata_bpom.png" class="logo-dark" alt="" height="50">
            </a>
            
            <button class="navbar-toggler me-3 order-2 ms-4" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-label="Toggle navigation">
                <i class="mdi mdi-menu"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mx-auto navbar-center">
                    <li class="nav-item">
                        <a href="index" class="nav-link ">Beranda</a>
                    </li>
                    <li class="nav-item dropdown dropdown-hover">
                        <a href="#layanan" class="nav-link ">Layanan</a>
                    </li>
                    <li class="nav-item dropdown dropdown-hover">
                        <a href="#inovasi" class="nav-link ">Inovasi</a>
                    </li>
                    <li class="nav-item dropdown dropdown-hover">
                        <a href="#pengunjung" class="nav-link ">Pengunjung</a>
                    </li>
                  
                  
                  
                </ul>
                <!--end navbar-nav-->
            </div>


            <!--end navabar-collapse-->
            
        </div>
        <!--end container-->
    </nav>
    <!-- Navbar End -->



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sign up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation">
                        <div class="col-md-6">
                            <label for="validationCustom01" class="form-label">First name <span
                                    class="text-primary">*</span></label>
                            <input type="text" class="form-control" id="validationCustom01" value="" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">Email <span
                                    class="text-primary">*</span></label>
                            <input type="email" class="form-control" id="validationCustom02" value="" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom03" class="form-label">Subject <span
                                    class="text-primary">*</span></label>
                            <input type="text" class="form-control" id="validationCustom03" required>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom05" class="form-label">Password <span
                                    class="text-primary">*</span></label>
                            <input type="password" class="form-control" id="validationCustom05" required>
                            <div class="invalid-feedback">
                                Please provide a valid zip.
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label for="validationTextarea" class="form-label">Textarea<span
                                    class="text-primary">*</span></label>
                            <textarea class="form-control" id="validationTextarea" required></textarea>
                            <div class="invalid-feedback">
                                You must agree before submitting.
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit form</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- end modal -->



    <!-- start home -->
    <section class="bg-home5" id="home">
        <div class="container-fluid p-0">
            <div class="row">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item carousel-box active"
                            style="background-image:url('images/home/2.jpg'); background-position: center; background-repeat: no-repeat;">
                            <div class="bg-overlay"></div>
                            <div class="home-center">
                                <div class="home-desc-center">
                                    <div class="container">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-lg-10">
                                                <div class="home-content text-center text-white">
                                                    <h1 class="home-title">PORTAL SPBE</h1>
                                                    <p class="home-4-desc text-white-50 mt-4 f-15"> Selamat Datang di Portal Informasi <br> Sistem Pemerintahan Berbasis Elektronik (SPBE) Balai Besar POM di Palangka Raya</p>
                                                    <div class="play-icon align-items-center justify-content-center">
                                                        <div class="watch-video mt-4">
                                                            <a href="" class="video-play-icon watch text-white"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#watchvideomodal">
                                                                <i
                                                                    class="mdi mdi-play text-center d-inline-block ms-lg-4 rounded-pill text-light bg-primary fs-20 avatar-md me-1"></i>
                                                                Video Profile Balai Besar POM di Palangka Raya</a>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade bd-example-modal-lg" id="watchvideomodal"
                                                        data-keyboard="false" tabindex="-1" aria-hidden="true">
                                                        <div
                                                            class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
                                                            <div class="modal-content hero-modal-0 bg-transparent">
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                                <video id="VisaChipCardVideo1" class="w-100" controls>
                                                                    <source
                                                                        src="video/PROFIL BBPOM DI PALANGKA RAYA.mp4"
                                                                        type="video/mp4" />
                                                                    <!--Browser does not support <video> tag -->
                                                                </video>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item carousel-box"
                        style="background-image:url('images/home/1.jpeg'); background-position: center; background-repeat: no-repeat;">
                            <div class="bg-overlay"></div>
                            <div class="home-center">
                                <div class="home-desc-center">
                                    <div class="container">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-lg-10">
                                                <div class="home-content text-center text-white">
                                                    <h1 class="home-title">PORTAL SPBE</h1>
                                                    <p class="home-4-desc text-white-50 mt-4 f-15">Selamat Datang di Portal Informasi <br> Sistem Pemerintahan Berbasis Elektronik (SPBE) Balai Besar POM di Palangka Raya</p>
                                                    <div class="mt-4 pt-2">
                                                        <a href="https://palangkaraya.pom.go.id/" target="_blank"
                                                            class="btn btn-outline-primary btn-rounded mr-3">Masuk Subsite Balai Besar POM di Palangka Raya <i class="mdi mdi-arrow-right ml-1"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

    </section>
    <!-- Hero End -->

  
    <!-- client section -->
    <section class="py-5 clients" id="layananeksternal">
        <div class="container">
            <div class="text-center">
                      
                <h4 class="mb-3">Layanan <span class="text-primary fw-normal">Publik </span>Balai Besar POM di Palangka Raya</h4><br>
                
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                      <div class="client-slidereksternal">
                        <div class="tiny-slide">
                            <a href="https://kahayan.bbpompky.id/" target="_blank">
                                <img src="images/clients/logo-kahayan-tutu-bahalap.png"  alt="" class="img-fluid">
                            </a>
                        </div>
                        <div class="tiny-slide">
                            <a href="https://berdikari.bbpompky.id/" target="_blank">
                                <img src="images/clients/berdikari.png" href="" alt="" class="img-fluid">
                            </a>
                        </div>
                        <div class="tiny-slide">
                            <a href="https://palangkaraya.pom.go.id/ppid/profil-ppid-pelaksana" target="_blank">
                                <img src="images/clients/apik.png" href="" alt="" class="img-fluid">
                            </a>
                        </div>
                       
                                            
                        <div class="tiny-slide">
                            <a href="https://sites.google.com/view/sanprabu/beranda?authuser=0" target="_blank">
                                <img src="images/clients/sangprabu.png" href="" alt="" class="img-fluid">
                            </a>
                        </div>
                        <div class="tiny-slide">
                            <a href="https://docs.google.com/forms/d/e/1FAIpQLScENGPbIH5mRadKxXkjbXroUi0DyTlQrVQ-X6U7OYRP_-MAhw/viewform" target="_blank">
                                <img src="images/clients/elamahamen.png" href="" alt="" class="img-fluid">
                            </a>
                        </div>
                       
                       
                        <div class="tiny-slide">
                            <a href="https://sites.google.com/view/kearsipanbbpompky/daduk-2023" target="_blank">
                                <img src="images/clients/asik.png" href="" alt="" class="img-fluid">
                            </a>
                        </div>
                        <div class="tiny-slide">
                            <a href="https://sippn.menpan.go.id/instansi/balai-besar-pom-di-palangkaraya-177702" target="_blank">
                                <img src="images/clients/logo-cariyanlik.svg" href="" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div>
                    <!--end client-slider-->
                </div>
                <!--end col-->
            </div>
        </div>
    </section>
    <!-- end section -->
  <!-- client section -->
    <section class="py-5 clients" id="layananinternal">
        <div class="container">
            <div class="text-center">
                      
                <h4 class="mb-3">Layanan <span class="text-primary fw-normal">Internal </span>Balai Besar POM di Palangka Raya</h4><br>
                
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                      <div class="client-sliderinternal">
                      
                      <div class="tiny-slide">
                        <a href="#" class="login-required" data-url="https://sites.google.com/view/monelaku">
                            <img src="images/clients/monelaku.png" alt="" class="img-fluid">
                        </a>
                        </div>

                            <div class="tiny-slide">
                        <a href="#" class="login-required" data-url="https://sites.google.com/view/qmsbbpomdipalangkaraya/halaman-muka?authuser=3">
                            <img src="images/clients/QMS.png" alt="" class="img-fluid">
                        </a>
                        </div>

                          <div class="tiny-slide">
                        <a href="#" class="login-required" data-url="https://drive.google.com/drive/folders/1VDjkj5Xn7TxwfI251SJLzWVslhpSUBlL">
                            <img src="images/clients/smap_new.png" alt="" class="img-fluid">
                        </a>
                        </div>
                     
                         <div class="tiny-slide">
                        <a href="#" class="login-required" data-url="https://sites.google.com/view/smmilpbbpompky/beranda">
                            <img src="images/clients/smii.png" alt="" class="img-fluid">
                        </a>
                        </div>

                         <div class="tiny-slide">
                        <a href="#" class="login-required" data-url="https://sites.google.com/view/kearsipanbbpompky/daduk-2023">
                            <img src="images/clients/asik.png" alt="" class="img-fluid">
                        </a>
                        </div>
                        
                       
                    </div>
                    <!--end client-slider-->
                </div>
                <!--end col-->
            </div>
        </div>
    </section>
    <!-- end section -->
  


    <!-- start blog -->
    <section class="section bg-light" id="inovasi">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center">
                      
                        <h2 class="mb-3">Inovasi <span class="text-primary fw-normal">Balai </span>Besar POM di Palangka Raya</h2>
                        
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-4 col-md-6 mt-4 mt-2">
                    <div class="blog-image overflow-hidden">
                        <img src="images/blog/1111.png" alt="" class="img-fluid w-100">
                    </div>
                    <div class="blog-content mb-2">
                       
                        <a href="">
                            <h4>Manajemen Perubahan</h4>
                        </a>
                    </div>
                 
                    <div class="blog-link">
                        <a href="" class="fw-bold f-14">Pelajari Lebih Lanjut <i class="mdi mdi-arrow-right align-middle"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-4 mt-2">
                    <div class="blog-image overflow-hidden">
                        <img src="images/blog/2222.png" alt="" class="img-fluid w-100">
                    </div>
                    <div class="blog-content mb-2">
                        
                        <a href="">
                            <h4>Penataan Tata Laksana</h4>
                        </a>
                    </div>
                    
                    <div class="blog-link">
                        <a href="" class="fw-bold f-14">Pelajari Lebih Lanjut <i class="mdi mdi-arrow-right align-middle"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6  mt-4 mt-2">
                    <div class="blog-image overflow-hidden">
                        <img src="images/blog/3333.png" alt="" class="img-fluid w-100">
                    </div>
                    <div class="blog-content">
                       
                        <a href="">
                            <h4 class="mt-3 fw-bold">Penataan SDM</h4>
                        </a>
                    </div>
                    
                    <div class="blog-link">
                        <a href="" class="fw-bold f-14">Pelajari Lebih Lanjut <i class="mdi mdi-arrow-right align-middle"></i></a>
                    </div>
                </div>
            </div>
            <!-- end row 1-->
            <div class="row mt-5">
                <div class="col-lg-4 col-md-6 mt-4 mt-2">
                    <div class="blog-image overflow-hidden">
                        <img src="images/blog/4444.png" alt="" class="img-fluid w-100">
                    </div>
                    <div class="blog-content mb-2">
                        
                        <a href="">
                            <h4>Penguatan Akuntabilitas</h4>
                        </a>
                    </div>
                    
                    <div class="blog-link">
                        <a href="" class="fw-bold f-14">Pelajari Lebih Lanjut <i class="mdi mdi-arrow-right align-middle"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-4 mt-2">
                    <div class="blog-image overflow-hidden">
                        <img src="images/blog/5555.png" alt="" class="img-fluid w-100">
                    </div>
                    <div class="blog-content mb-2">
                       
                        <a href="">
                            <h4>Pengawasan</h4>
                        </a>
                    </div>
                  
                    <div class="blog-link">
                        <a href="" class="fw-bold f-14">Pelajari Lebih Lanjut <i class="mdi mdi-arrow-right align-middle"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6  mt-4 mt-2">
                    <div class="blog-image overflow-hidden">
                        <img src="images/blog/6666.png" alt="" class="img-fluid w-100">
                    </div>
                    <div class="blog-content">
                        
                        <a href="">
                            <h4 class="mt-3 fw-bold">Peningkatan Kualitas Pelayanan Publik</h4>
                        </a>
                    </div>
                  
                    <div class="blog-link">
                        <a href="" class="fw-bold f-14">Pelajari Lebih Lanjut <i class="mdi mdi-arrow-right align-middle"></i></a>
                    </div>
                </div>
            </div>
                <!-- end row 2 -->
            
        </div>
    </section>
    <!-- end blog -->

  

    <!-- counter section-->
    <section class="counter ">
        <div class="container">
        <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center">
                      
                        <h2 class="mb-3">Statistik <span class="text-primary fw-normal">Pengunjung </span></h2>
                        
                    </div>
                </div>
        </div>
            <div class="row" id="pengunjung">
                <div class="col-lg-3 p-lg-0">
                    <div class="counter-box py-0 py-lg-5 text-center position-relative">
                        <div class="side-border-left"></div>
                        <div class="py-5 pb-0">
                            <div class="counter-image">
                                <img src="images/counter/visitoroke.png" alt="">
                            </div>
                            <h2 class="counter_value fw-bold mt-2 text-primary" data-bs-target="<?php echo $totpengunjung; ?>"><?php echo $totpengunjung; ?></h2>
                            <h5 class="counter-caption mb-0 mt-3">Total Pengunjung</h5>
                        </div>
                        <div class="side-border-right"></div>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-3 p-lg-0">
                    <div class="counter-box py-0 py-lg-5 text-center">
                        <div class="py-5 pb-0">
                            <img src="images/counter/hariini.png" alt="">
                            <h2 class="counter_value fw-bold mt-2 text-primary" data-bs-target="<?php echo $pengunjung; ?>">0<?php echo $pengunjung; ?></h2>
                            <h5 class="counter-caption mb-0 mt-3">Pengunjung Hari Ini</h5>
                        </div>
                        <div class="side-border-right"></div>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-3 p-lg-0">
                    <div class="counter-box py-0 py-lg-5 text-center side-border">
                        <div class="py-5 pb-0">
                            <img src="images/counter/online.png" alt="">
                            <h2 class="counter_value fw-bold mt-2 text-primary" data-bs-target="<?php echo $pengunjungonline; ?>"><?php echo $pengunjungonline; ?></h2>
                            <h5 class="counter-caption mb-0 mt-3">Pengunjung Online</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 p-lg-0">
                    <div class="counter-box py-0 py-lg-5 text-center side-border">
                        <div class="py-5 pb-0">
                            <img src="images/counter/online.png" alt="">
                            <h2 class="counter_value fw-bold mt-2 text-primary" data-bs-target="<?php echo $tothits; ?>"><?php echo $tothits; ?></h2>
                            <h5 class="counter-caption mb-0 mt-3">Total Hits</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 p-lg-0">
                    <div class="counter-box py-0 py-lg-5 text-center side-border">
                        <div class="py-5 pb-0">
                            <img src="images/counter/online.png" alt="">
                            <h2 class="counter_value fw-bold mt-2 text-primary" data-bs-target="<?php echo $totmonth; ?>"><?php echo $totmonth; ?></h2>
                            <h5 class="counter-caption mb-0 mt-3">Pengunjung Bulan Ini</h5>
                        </div>
                    </div>
                </div>

             

                <div class="col-lg-3 p-lg-0">
                    <div class="counter-box py-0 py-lg-5 text-center side-border">
                        <div class="py-5 pb-0">
                            <img src="images/counter/online.png" alt="">
                            <h2 class="counter_value fw-bold mt-2 text-primary" data-bs-target="<?php echo $pengunjungLastYear ; ?>"><?php echo $pengunjungLastYear ; ?></h2>
                            <h5 class="counter-caption mb-0 mt-3">Pengunjung Tahun Lalu</h5>
                        </div>
                    </div>
                </div>

                   <div class="col-lg-3 p-lg-0">
                    <div class="counter-box py-0 py-lg-5 text-center side-border">
                        <div class="py-5 pb-0">
                            <img src="images/counter/online.png" alt="">
                            <h2 class="counter_value fw-bold mt-2 text-primary" data-bs-target="<?php echo $pengunjungThisYear; ?>"><?php echo $pengunjungThisYear; ?></h2>
                            <h5 class="counter-caption mb-0 mt-3">Pengunjung Tahun Ini</h5>
                        </div>
                    </div>
                </div>

         

                <!-- end col -->
             
                <!-- end col -->
            </div>
            <!-- end row -->
            
        </div>
        <!-- end container -->
    </section>
    <!-- end counter -->



    <!-- start footer -->
    <section class="footer py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="address-content fw-bold">
                        <h5 class="text-black">Alamat</h5>
                        <p class="mt-5  f-14 ">Jalan Tjilik Riwut KM 3,5 No 13 Palangka Raya</p>
                        <p class="mt-3 f-14 ">+62811555633 (7.30 - 16.00, Senin - Kamis) (7.30 - 16.30, Jumat)</p>
                        <a href="" class=" f-14">bpom_palangkaraya@pom.go.id</a>
                    </div>
                    <div class="social-icon d-flex mt-4 mb-4 mb-lg-0">
                        <div class="facebook">
                            <a href="https://api.whatsapp.com/send?phone=6285822236111&" target="_blank"><i class="mdi mdi-whatsapp f-30"></i></a>
                        </div>
                        <div class="twitter ms-4">
                            <a href="https://www.youtube.com/@bpom.palangkaraya" target="_blank"><i class="mdi mdi-youtube-play f-30"></i></a>
                        </div>
                        <div class="twitter ms-4">
                            <a href="https://twitter.com/bpompalangka"  target="_blank"><i class="mdi mdi-twitter f-30"></i></a>
                        </div>
                        <div class="twitter ms-4">
                            <a href="https://www.facebook.com/bpom.palangkaraya/"  target="_blank"><i class="mdi mdi-facebook-box f-30"></i></a>
                        </div>
                        
                        <div class="twitter ms-4">
                            <a href="https://www.instagram.com/bpom.palangkaraya/" target="_blank" ><i class="mdi mdi-instagram f-30"></i></a>
                        </div>
                        
                        
                        
                    </div>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-black">Link Terkait</h5>
                    <ul class="menu list-unstyled mt-5">
                        <li class="menu-item"><a href="https://www.pom.go.id/" target="_blank" >Website Badan POM</a></li>
                        <li class="menu-item"><a href="https://palangkaraya.pom.go.id/" target="_blank">Subsite BBPOM di Palangka Raya</a></li>
                        <li class="menu-item"><a href="https://www.berdikari.bbpompky.id/" target="_blank">UMKM Berdikari</a></li>
                        <li class="menu-item"><a href="https://www.kahayan.bbpompky.id/" target="_blank">Kahayan</a></li>
                        <li class="menu-item"><a href="https://www.kahayan.bbpompky.id/" target="_blank">Kahayan</a></li>
                        
                    </ul>

                </div>
                
            </div>

            
        </div>
    </section>
    <!-- end footer -->

    <div class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="copy-right mb-5 text-center text-muted">
                        <script>document.write(new Date().getFullYear())</script> BBPOM di Palangka Raya. Design with <i
                            class="mdi mdi-heart text-danger"></i> by <a
                            href="https://rolinkstone.wordpress.com" target="_blank"
                            class="text-reset">TIM TI BBPOM di Palangka Raya</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- bootstrap -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <script src="js/tiny-slider.js"></script>

    <script src="js/particles.js"></script>
    <script src="js/particles.app.js"></script>

    <script src="js/swiper.min.js"></script>

    <!-- counter -->
    <script src="js/counter.init.js"></script>

    <!-- Custom -->
    <script src="js/app.js"></script>

<script>
document.querySelectorAll('.login-required').forEach(link => {
  link.addEventListener('click', function(e){
    e.preventDefault();
    const targetUrl = this.getAttribute('data-url');
    document.getElementById('redirect-url').value = targetUrl;
    document.getElementById('loginError').style.display = 'none';
    document.getElementById('loginError').innerText = '';
    document.getElementById('loginForm').reset();
    new bootstrap.Modal(document.getElementById('loginModal')).show();
  });
});

document.getElementById('loginForm').addEventListener('submit', function(e){
  e.preventDefault();
  const formData = new FormData(this);

  fetch('login.php', {
    method: 'POST',
    body: formData,
    credentials: 'same-origin'
  })
  .then(res => res.json())
  .then(data => {
    if(data.status === 'success'){
      bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
      window.open(data.redirect, '_blank');
    } else {
      const el = document.getElementById('loginError');
      el.style.display = 'block';
      el.innerText = data.msg || 'Login gagal';
    }
  })
  .catch(err => {
    const el = document.getElementById('loginError');
    el.style.display = 'block';
    el.innerText = 'Terjadi kesalahan jaringan.';
    console.error(err);
  });
});
</script>


</body>

</html>