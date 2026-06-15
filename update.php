<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit();
}

include "config.php";

$message = "";

$success = false;

$id = $_GET['id'] ?? '';

if(!$id){
    die("ID movie tidak ditemukan");
}

$url = $base_url . "get_movie.php?id=".$id;

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "API_KEY: ".$api_key
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

if(!$data){
    die("Data movie tidak ditemukan");
}

if($data['status'] != "success"){
    die("Data movie tidak ditemukan");
}

$movie = $data['data'];

if(isset($_POST['update'])){

    $postData = [
        "title" => $_POST['title'],
        "genre" => $_POST['genre'],
        "year" => $_POST['year'],
        "rating" => $_POST['rating']
    ];

    $curl = curl_init();

    curl_setopt_array($curl,[
        CURLOPT_URL => $base_url."update_movie.php?id=".$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "API_KEY: ".$api_key
        ]
    ]);

    $response = curl_exec($curl);

    if(curl_errno($curl)){
        $message = curl_error($curl);
    }

    curl_close($curl);

    $hasil = json_decode($response, true);

    if($hasil && isset($hasil['status'])){

    if($hasil['status'] == "success"){
        $success = true;
    }else{
            $message = $hasil['message'] ?? "Gagal mengupdate data.";
        }
    }else{
        $message = "Response API tidak valid:<br><pre>".$response."</pre>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Movie</title>
<link rel="stylesheet" href="css/style_update.css">
</head>
<body>
<div class="main-content">

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
                <h1>✏️ Edit Movie</h1>

                <p>
                    Gunakan halaman ini untuk memperbarui informasi movie yang telah tersimpan pada database melalui Movie API.
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
                    🎬 Movie ID
                    <br><br>

                    #<?= $movie['id']; ?>
                </div>

                <div class="quote">
                    ❝ Perbarui informasi movie agar data yang tersimpan selalu akurat dan terbaru.
                </div>
            </div>

            <div class="form-box">

                <h2>📝 Form Edit Movie</h2>

                <form method="POST" class="movie-form">
                    <label>Title</label>
                    <input
                    type="text"
                    name="title"
                    value="<?= $movie['title']; ?>"
                    required>

                    <label>Genre</label>
                    <input
                    type="text"
                    name="genre"
                    value="<?= $movie['genre']; ?>"
                    required>

                    <label>Year</label>
                    <input
                    type="number"
                    name="year"
                    value="<?= $movie['year']; ?>"
                    required>

                    <label>Rating</label>
                    <input
                    type="number"
                    step="0.1"
                    name="rating"
                    value="<?= $movie['rating']; ?>"
                    required>

                    <div class="bottom-action">

                        <button name="update">
                            💾 Update Movie
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

<?php if($success): ?>

<script>

Swal.fire({
    icon: 'success',
    title: 'Update Berhasil',
    text: 'Data movie berhasil diperbarui.',
    confirmButtonColor: '#243b55',
    confirmButtonText: 'OK',
    allowOutsideClick: false
}).then(function(){

    window.location = "index.php";

});

</script>

<?php endif; ?>
</body>
</html>