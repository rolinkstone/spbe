<?php
session_start();

// Buat CSRF token jika belum ada
if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
$ip      = $_SERVER['REMOTE_ADDR']; // Dapatkan IP user
$tanggal = date("Ymd"); // Dapatkan tanggal sekarang
$waktu   = time(); // Dapatkan nilai waktu
$bln = date("m");
$thn = date("Y");
$konek = mysqli_connect("localhost","bbps6532_spbe","tugaskitA1!","bbps6532_portal");
if(!$konek){ die(mysqli_connect_error()); }

// Cek apakah IP sudah ada hari ini
$stmt = $konek->prepare("SELECT * FROM statistik WHERE ip=? AND tanggal=?");
$stmt->bind_param("ss", $ip, $tanggal);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    // Insert baru
    $insert = $konek->prepare("INSERT INTO statistik(ip, tanggal, hits, online) VALUES(?,?,1,?)");
    $insert->bind_param("ssi", $ip, $tanggal, $waktu);
    $insert->execute();
} else {
    // Update online timestamp setiap akses
    $update = $konek->prepare("UPDATE statistik SET online=?, hits = hits + 1 WHERE ip=? AND tanggal=?");
    $update->bind_param("iss", $waktu, $ip, $tanggal);
    $update->execute();
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



// Hitung pengunjung online
$bataswaktu = time() - 300; // 5 menit
$pengunjungonline = mysqli_num_rows(mysqli_query($konek, "SELECT * FROM statistik WHERE online > '$bataswaktu'"));

// Angka total pengunjung dalam bentuk gambar
$folder = "counter"; // nama folder
$ext    = ".png";     // ekstension file gambar

// ubah digit angka menjadi enam digit
$totpengunjunggbr = sprintf("%06d", $totpengunjung);
// ganti angka teks dengan angka gambar


?> 