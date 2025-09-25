<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-container {
            text-align: center;
        }
        .error-code {
            font-size: 10rem;
            font-weight: bold;
            color: #0d6efd;
        }
        .error-text {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .error-desc {
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .btn-home {
            background-color: #0d6efd;
            color: #fff;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
        }
        .btn-home:hover {
            background-color: #0b5ed7;
            color: #fff;
        }
        @media (max-width: 576px) {
            .error-code { font-size: 6rem; }
            .error-text { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-text">Halaman Tidak Ditemukan</div>
        <div class="error-desc">Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.</div>
        <a href="index.php" class="btn-home">Kembali ke Beranda</a>
    </div>
</body>
</html>
