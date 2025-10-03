<?php
header('Content-Type: application/json; charset=UTF-8');

$url = "https://palangkaraya.pom.go.id/berita";

// Fungsi ambil HTML pakai cURL
function get_web_content($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // kalau sertifikat SSL bermasalah
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MyScraper/1.0)");
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($result === false || empty($result)) {
        return ["error" => "Gagal mengambil halaman: $err"];
    }
    return $result;
}

// Ambil HTML
$html = get_web_content($url);
if (is_array($html) && isset($html['error'])) {
    echo json_encode($html, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Load HTML ke DOM
$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($doc);

// Cari semua article
$articles = $xpath->query("//article[contains(@class,'col-6')]");

$berita = [];
foreach ($articles as $article) {
    // Judul + link
    $judulNode = $xpath->query(".//a[contains(@class,'text-dark')]", $article)->item(1);
    if (!$judulNode) continue;

    $judul = trim($judulNode->textContent);
    $link  = $judulNode->getAttribute("href");

    if (strpos($link, "http") === false) {
        $link = "https://palangkaraya.pom.go.id" . $link;
    }

    // Gambar
    $imgNode = $xpath->query(".//a[contains(@class,'text-dark')][1]//img", $article)->item(0);
    $gambar = "";
    if ($imgNode) {
        $gambar = $imgNode->getAttribute("src");
        if ($gambar && strpos($gambar, "http") === false) {
            $gambar = "https://palangkaraya.pom.go.id" . $gambar;
        }
    }

    // Ringkasan isi berita
    $ringkasanNode = $xpath->query(".//p[contains(@class,'card-text')]", $article)->item(0);
    $ringkasan = $ringkasanNode ? trim($ringkasanNode->textContent) : "";

    $berita[] = [
        "judul"    => $judul,
        "link"     => $link,
        "gambar"   => $gambar,
        "ringkasan"=> $ringkasan
    ];
}

// Ambil hanya 6 berita terbaru
$berita = array_slice($berita, 0, 6);

echo json_encode($berita, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
