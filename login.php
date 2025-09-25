<?php
header('Content-Type: application/json');
session_start();

$konek = mysqli_connect('localhost', 'root', '', 'bbps6532_portal');
if(!$konek){
    echo json_encode(['status'=>'error','msg'=>'Database error']);
    exit;
}

$username = mysqli_real_escape_string($konek, $_POST['username'] ?? '');
$password = mysqli_real_escape_string($konek, $_POST['password'] ?? '');
$redirect = $_POST['redirect'] ?? 'index.php';

if($username && $password){
    // Misal table users: id, username, password (hashed)
    $query = mysqli_query($konek, "SELECT * FROM users WHERE username='$username'");
    if($user = mysqli_fetch_assoc($query)){
        // cek password (hash disarankan)
        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user['username'];
            echo json_encode(['status'=>'success','redirect'=>$redirect]);
        } else {
            echo json_encode(['status'=>'error','msg'=>'Username atau password salah!']);
        }
    } else {
        echo json_encode(['status'=>'error','msg'=>'Username atau password salah!']);
    }
} else {
    echo json_encode(['status'=>'error','msg'=>'Username & password wajib diisi']);
}
?>
