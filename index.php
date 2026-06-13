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

$limit = 15;

$page = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;

$total_movie = count($movies);

$total_page = ceil($total_movie / $limit);

$start = ($page - 1) * $limit;

$movies = array_slice($movies, $start, $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>Movie Client</title>

<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    font-size:14px;
    background:#f4f6f9;
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
    margin:0;
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
    width:85%;
    max-width:1200px;
    margin:auto;
}

.hero{
    margin-top:25px;
    background:white;
    padding:20px 25px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
}

.hero h1{
    margin-top:0;
    font-size:28px;
    color:#243b55;
}

.hero p{
    font-size:14px;
    color:#555;
}

.btn{
    display:inline-block;
    padding:10px 16px;
    background:#243b55;
    color:white;
    text-decoration:none;
    border-radius:6px;
    margin-top:10px;
    font-size:14px;
}

.cards{
    margin-top:20px;
    display:flex;
    gap:15px;
}

.card{
    flex:1;
    background:white;
    padding:18px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
}

.card h1{
    margin:0;
    font-size:28px;
    color:#243b55;
}

.card p{
    margin-top:8px;
    font-size:14px;
    color:#666;
}

.table-box{
    margin-top:25px;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
}

.table-box h2{
    margin-top:0;
    font-size:22px;
    color:#243b55;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#243b55;
    color:white;
    font-size:14px;
}

th,td{
    padding:10px 12px;
    text-align:center;
    border-bottom:1px solid #ddd;
    font-size:14px;
}

tr:hover{
    background:#f2f2f2;
}

.action-btn{
    display:inline-block;
    padding:5px 10px;
    color:white;
    text-decoration:none;
    border-radius:5px;
    font-size:12px;
    transition:.3s;
    margin:0 2px;
}

.edit-btn{
    background:#3498db;
}

.edit-btn:hover{
    background:#2980b9;
}

.delete-btn{
    background:#e74c3c;
}

.delete-btn:hover{
    background:#c0392b;
}

.pagination{
    margin-top:20px;
    text-align:center;
}

.pagination a{
    display:inline-block;
    padding:8px 14px;
    margin:0 3px;
    background:#243b55;
    color:white;
    text-decoration:none;
    border-radius:6px;
    font-size:13px;
    transition:.3s;
}

.pagination a:hover{
    background:#1b3870;
}

.pagination .active{
    background:#3498db;
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
            <h1><?= $total_movie; ?></h1>
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
                    <a
                        href="update.php?id=<?= $movie['id']; ?>"
                        class="action-btn edit-btn">
                        ✏ Edit
                    </a>

                    <a
                        href="hapus.php?id=<?= $movie['id']; ?>"
                        class="action-btn delete-btn"
                        onclick="return confirm('Hapus movie ini?')">
                        🗑 Hapus
                    </a>
                </td>

            </tr>
            
            <?php endforeach; ?>

        </table>
        <div class="pagination">

            <?php for($i=1; $i<=$total_page; $i++): ?>

                <a
                    href="?page=<?= $i; ?>"
                    class="<?= ($page == $i) ? 'active' : ''; ?>">

                    <?= $i; ?>

                </a>

            <?php endfor; ?>

        </div>
    </div>

    <div class="footer">
        <p>
            Movie Client • Consumer of Movie API • 2026
        </p>

    </div>

</div>

</body>
</html>