<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit();
}

include "config.php";

$message = "";

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

            header("Location: index.php");
            exit();

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

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f5f5f5;
    min-height:100vh;
}

.main-content{
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

.navbar{
    background:linear-gradient(to right,#111d3c,#1f3768);
    color:white;
    padding:25px 50px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.navbar h2{
    font-size:22px;
}

.logout{
    background:#ff1f2d;
    color:white;
    text-decoration:none;
    padding:10px 18px;
    border-radius:10px;
    font-size:14px;
    font-weight:bold;
}

.container{
    flex:1;
    width:90%;
    margin:40px auto;
    display:flex;
    gap:30px;
    align-items:stretch;
}

.info{
    width:35%;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);

    display:flex;
    flex-direction:column;
}

.info h1{
    color:#20375e;
    font-size:28px;
    margin-bottom:25px;
}

.info p{
    color:#666;
    line-height:1.8;
}

.box-info{
    padding:20px 0;
    border-bottom:1px solid #e8e8e8;
    font-size:14px;
}

.quote{
    margin-top:25px;
    background:#f0e8ff;
    color:#6945d8;
    padding:18px;
    border-radius:15px;
    line-height:1.7;
}

.form-box{
    flex:1;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);

    display:flex;
    flex-direction:column;
}

.movie-form{
    flex:1;
    display:flex;
    flex-direction:column;
}

.bottom-action{
    margin-top:auto;
}

.msg{
    color:red;
    margin-top:15px;
}

.back{
    display:inline-block;
    margin-top:25px;
    color:#6945d8;
    text-decoration:none;
    font-weight:bold;
}

.form-box h2{
    color:#20375e;
    margin-bottom:30px;
    font-size:20px;
}

input{
    width:100%;
    padding:18px;
    border:1px solid #e2e2e2;
    border-radius:12px;
    margin-bottom:15px;
    font-size:14px;
    background:white;
}

button{
    width:100%;
    padding:17px;
    border:none;
    border-radius:12px;
    background:#122753;
    color:white;
    font-size:15px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#1b3870;
}

.msg{
    color:red;
    margin-top:15px;
}

.back{
    display:inline-block;
    margin-top:25px;
    color:#6945d8;
    text-decoration:none;
    font-weight:bold;
}

.footer{
    border-top:1px solid #d9d9d9;
    text-align:center;
    padding:25px;
    color:#777;
    font-size:13px;
    margin-top:auto;
}
</style>

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

                    <input
                    type="text"
                    name="title"
                    value="<?= $movie['title']; ?>"
                    required>

                    <input
                    type="text"
                    name="genre"
                    value="<?= $movie['genre']; ?>"
                    required>

                    <input
                    type="number"
                    name="year"
                    value="<?= $movie['year']; ?>"
                    required>

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

                        <a href="index.php" class="back">
                            ← Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="footer">
            Movie Client • Consumer of Movie API • 2026
        </div>

</div>
</body>
</html>