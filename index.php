<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit();
}

include "config.php";

$curl = curl_init();

curl_setopt_array($curl,[
    CURLOPT_URL=>$base_url."get_movies.php",
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_HTTPHEADER=>[
        "API_KEY: ".$_SESSION['api_key']
    ]
]);

$response = curl_exec($curl);

curl_close($curl);

$response = json_decode($response,true);

$movies = $response['data'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Movie Client</title>

<style>

body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

.navbar{
    background:#243b55;
    color:white;
    padding:20px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logout{
    text-decoration:none;
    color:white;
    background:red;
    padding:10px 15px;
    border-radius:8px;
}

.container{
    width:90%;
    margin:auto;
}

.hero{
    margin-top:30px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

.cards{
    margin-top:25px;
    display:flex;
    gap:20px;
}

.card{
    flex:1;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

.card h1{
    margin:0;
    color:#243b55;
}

.table-box{
    margin-top:30px;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#243b55;
    color:white;
}

th,td{
    padding:15px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f2f2f2;
}

.btn{
    display:inline-block;
    padding:12px 20px;
    background:#243b55;
    color:white;
    text-decoration:none;
    border-radius:8px;
    margin-top:15px;
}

.footer{
    margin-top:40px;
    text-align:center;
    color:gray;
}

</style>

</head>
<body>

<div class="navbar">

    <h2>🎥 MOVIE CLIENT</h2>

    <a href="logout.php" class="logout">
        Logout
    </a>

</div>

<div class="container">

    <div class="hero">

        <h1>
            Selamat Datang,
            <?= $_SESSION['name']; ?>
        </h1>

        <p>
            Website ini merupakan implementasi Movie API menggunakan PHP dan cURL.
        </p>

        <a href="tambah.php" class="btn">
            + Tambah Movie
        </a>

    </div>


    <div class="cards">

        <div class="card">
            <h1><?= count($movies); ?></h1>
            <p>Total Movie</p>
        </div>

        <div class="card">
            <h1>Connected</h1>
            <p>Status API</p>
        </div>

        <div class="card">
            <h1><?= $_SESSION['name']; ?></h1>
            <p>User Aktif</p>
        </div>

    </div>


    <div class="table-box">

        <h2>Daftar Movie</h2>

        <table>

            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>

            <?php foreach($movies as $movie): ?>

            <tr>

                <td><?= $movie['id']; ?></td>

                <td><?= $movie['title']; ?></td>

                <td><?= $movie['year']; ?></td>

                <td>

                    <a href="edit.php?id=<?= $movie['id']; ?>">
                        Edit
                    </a>

                    |

                    <a
                    href="hapus.php?id=<?= $movie['id']; ?>"
                    onclick="return confirm('Hapus movie ini?')"
                    >
                        Hapus
                    </a>

                </td>

            </tr>

            <?php endforeach; ?>

        </table>

    </div>

    <div class="footer">

        <p>
            Movie Client • Consumer of Movie API • 2026
        </p>

    </div>

</div>

</body>
</html>