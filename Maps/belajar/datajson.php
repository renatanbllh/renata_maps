<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "belajar_maps";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query SQL
$sql = "
SELECT a.kode_negara, a.nm_negara, b.lon, b.lat, SUM(vol_kg) AS volume 
FROM data_impor a
JOIN world_country b ON a.kode_negara=LEFT(b.kode_negara,2)
WHERE _exim='impor'
GROUP BY kode_negara, nm_negara
";

$result = $conn->query($sql);

// Ambil data dan kembalikan dalam format JSON
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = array(
            'kode_negara' => $row['kode_negara'],
            'nm_negara' => $row['nm_negara'],
            'koordinat' => [$row['lat'], $row['lon']],
            'volume' => $row['volume']
        );
    }
}

// Tutup koneksi
$conn->close();

// Kembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
