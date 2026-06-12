<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit();
}

include "config.php";

$message = "";

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

        header("Location: index.php");
        exit();

    }else{

        $message = $response['message'];

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
    background:#f4f6f9;
    font-size:14px;
    min-height:100vh;
}

.main-content{
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

.navbar{
    background:#243b55;
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.navbar h2{
    font-size:22px;
}

.logout{
    text-decoration:none;
    color:white;
    background:red;
    padding:8px 12px;
    border-radius:6px;
    font-size:13px;
}

.container{
    flex:1;
    width:85%;
    max-width:1200px;
    margin:25px auto;
    display:flex;
    gap:20px;
    align-items:stretch;
}

.info{
    width:35%;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
    display:flex;
    flex-direction:column;
}

.info h1{
    color:#243b55;
    font-size:24px;
    margin-bottom:20px;
}

.info p{
    color:#666;
    line-height:1.7;
    font-size:14px;
}

.box-info{
    padding:15px 0;
    border-bottom:1px solid #e8e8e8;
    font-size:14px;
}

.quote{
    margin-top:20px;
    background:#f0e8ff;
    color:#6945d8;
    padding:15px;
    border-radius:10px;
    line-height:1.6;
    font-size:13px;
}

.form-box{
    flex:1;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
    display:flex;
    flex-direction:column;
}

.form-box h2{
    color:#243b55;
    margin-bottom:25px;
    font-size:22px;
}

.movie-form{
    flex:1;
    display:flex;
    flex-direction:column;
}

input{
    width:100%;
    padding:12px 15px;
    border:1px solid #ddd;
    border-radius:8px;
    margin-bottom:15px;
    font-size:14px;
    outline:none;
}

input:focus{
    border-color:#243b55;
}

.bottom-action{
    margin-top:auto;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#243b55;
    color:white;
    font-size:14px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#1b3870;
}

.msg{
    color:red;
    margin-top:12px;
    font-size:13px;
}

.back{
    display:inline-block;
    margin-top:20px;
    color:#6945d8;
    text-decoration:none;
    font-weight:bold;
    font-size:13px;
}

.footer{
    margin-top:30px;
    margin-bottom:20px;
    text-align:center;
    color:gray;
    font-size:13px;
}

</style>

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

                <input
                type="text"
                name="title"
                placeholder="Judul Movie"
                required>

                <input
                type="text"
                name="genre"
                placeholder="Genre"
                required>

                <input
                type="number"
                name="year"
                placeholder="Tahun"
                required>

                <input
                type="number"
                step="0.1"
                name="rating"
                placeholder="Rating"
                required>


                <div class="bottom-action">

                    <button name="simpan">
                        💾 Simpan Movie
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