<?php
session_start();
header('Content-Type: application/json');

// Buat CSRF token jika belum ada
if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Koneksi database
$konek = mysqli_connect("localhost","bbps6532_spbe","tugaskitA1!","bbps6532_portal");
if(!$konek){
    echo json_encode(['status'=>'error','msg'=>'Database error']);
    exit;
}

// Ambil input
$username = mysqli_real_escape_string($konek, $_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$csrf_token = $_POST['csrf_token'] ?? '';
$redirect = $_POST['redirect'] ?? 'index.php';

// Cek CSRF token
if($csrf_token !== $_SESSION['csrf_token']){
    echo json_encode(['status'=>'error','msg'=>'Invalid CSRF token']);
    exit;
}

// Batasi percobaan login
if(!isset($_SESSION['login_attempts'])){
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = 0;
}
$lockout_time = 5 * 60; // 5 menit
if($_SESSION['login_attempts'] >= 5){
    $elapsed = time() - $_SESSION['last_attempt_time'];
    if($elapsed < $lockout_time){
        $remaining = ceil(($lockout_time - $elapsed)/60);
        echo json_encode(['status'=>'error','msg'=>"Terlalu banyak percobaan login. Coba lagi dalam $remaining menit."]);
        exit;
    } else {
        // reset percobaan setelah waktu lockout habis
        $_SESSION['login_attempts'] = 0;
    }
}

// Pastikan username & password diisi
if(!$username || !$password){
    echo json_encode(['status'=>'error','msg'=>'Username & password wajib diisi']);
    exit;
}

// Ambil user dari database
$query = mysqli_query($konek, "SELECT * FROM users WHERE username='$username'");
if($user = mysqli_fetch_assoc($query)){
    if(password_verify($password, $user['password'])){
        // Login berhasil
        $_SESSION['user'] = $user['username'];
        $_SESSION['login_attempts'] = 0; // reset percobaan
        echo json_encode(['status'=>'success','redirect'=>$redirect]);
    } else {
        // Password salah
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
        echo json_encode(['status'=>'error','msg'=>'Username atau password salah!']);
    }
} else {
    // User tidak ditemukan
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt_time'] = time();
    echo json_encode(['status'=>'error','msg'=>'Username atau password salah!']);
}
?>
