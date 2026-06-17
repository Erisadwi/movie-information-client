<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit();
}

include "config.php";

$id = $_GET['id'] ?? '';

if(!$id){
    header("Location: index.php");
    exit();
}

$curl = curl_init();

curl_setopt_array($curl,[
    CURLOPT_URL => $base_url."delete_movie.php?id=".$id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "DELETE",
    CURLOPT_HTTPHEADER => [
        "API_KEY: ".$api_key
    ]
]);

$response = curl_exec($curl);

curl_close($curl);

$hasil = json_decode($response, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Movie</title>

    <link rel="stylesheet" href="css/style_update.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .swal2-popup{
            font-family: inherit !important;
        }
    </style>
</head>
<body>

<?php if($hasil && $hasil['status'] == "success"): ?>

<script>

Swal.fire({
    icon: 'success',
    title: 'Hapus Berhasil',
    text: 'Data movie berhasil dihapus.',
    confirmButtonColor: '#243b55',
    confirmButtonText: 'OK',
    allowOutsideClick: false
}).then(function(){

    window.location = "index.php";

});

</script>

<?php else: ?>

<script>

Swal.fire({
    icon: 'error',
    title: 'Hapus Gagal',
    text: 'Data movie gagal dihapus.',
    confirmButtonColor: '#243b55',
    confirmButtonText: 'OK'
}).then(function(){

    window.location = "index.php";

});

</script>

<?php endif; ?>

</body>
</html>
