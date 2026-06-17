<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit();
}

include "config.php";

$message = "";
$success = false;

if(isset($_POST['simpan'])){

    $data = [
        "title"  => $_POST['title'],
        "genre"  => $_POST['genre'],
        "year"   => $_POST['year'],
        "rating" => $_POST['rating']
    ];

    $curl = curl_init();

    curl_setopt_array($curl,[
        CURLOPT_URL => $base_url."add_movie.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "API_KEY: ".$_SESSION['api_key']
        ]
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response,true);

    if($response['status'] == "success"){

        $success = true;

    }else{

        $message = $response['message'];

    }

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Movie</title>
<link rel="stylesheet" href="css/style_tambah.css">
</head>
<body>
<div class="main-content">
    <div class="navbar">
        <h2>🎬 MOVIE CLIENT</h2>
        <a href="logout.php" class="logout">
            Logout
        </a>
    </div>

    <div class="container">
        <div class="info">
            <h1>🎬 Tambah Movie Baru</h1>

            <p>
                Gunakan halaman ini untuk menambahkan data movie ke database melalui Movie API.
            </p>

            <div class="box-info">
                👤 User
                <br><br>
                <b><?= $_SESSION['name']; ?></b>
            </div>

            <div class="box-info">
                🔗 API Status
                <br><br>

                <span style="color:green;font-weight:bold;">
                    Connected
                </span>
            </div>

            <div class="box-info">
                🎬 Consumer of
                <br><br>

                Movie API
            </div>

            <div class="quote">
                ❝ Kelola data movie dengan mudah menggunakan Movie API.
            </div>
        </div>

        <div class="form-box">
            <h2>➕ Form Tambah Movie</h2>

            <form method="POST" class="movie-form">
                <label>Title</label>
                <input
                type="text"
                name="title"
                placeholder="Masukkan Judul Movie"
                required>

                <label>Genre</label>
                <input
                type="text"
                name="genre"
                placeholder="Masukkan Genre"
                required>

                <label>Year</label>
                <input
                type="number"
                name="year"
                placeholder="Masukkan Tahun"
                required>

                <label>Rating</label>
                <input
                type="number"
                step="0.1"
                name="rating"
                placeholder="Masukkan Rating"
                required>

                <div class="bottom-action">
                    <button name="simpan">
                        💾 Simpan Movie
                    </button>

                    <div class="msg">
                        <?= $message ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        Movie Client • Consumer of Movie API • 2026
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.swal2-popup{
    font-family: inherit !important;
}
</style>

<?php if($success): ?>

<script>

Swal.fire({
    icon: 'success',
    title: 'Tambah Berhasil',
    text: 'Data movie berhasil ditambahkan.',
    confirmButtonColor: '#243b55',
    confirmButtonText: 'OK',
    allowOutsideClick: false
}).then(function(){

    window.location = "dashboard.php";

});

</script>

<?php endif; ?>
</body>
</html>