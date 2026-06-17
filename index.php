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
<link rel="stylesheet" href="css/style_index.css">
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

        <p>Website ini merupakan implementasi Movie API menggunakan PHP dan cURL.</p>

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
                        href="#"
                        class="action-btn delete-btn"
                        onclick="openDeleteModal(<?= $movie['id']; ?>)">
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
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <h2>🗑 Hapus Movie</h2>

        <p>
            Apakah Anda yakin ingin menghapus movie ini?
        </p>

        <div class="modal-button">
            <button class="cancel-btn"
                    onclick="closeDeleteModal()">
                Batal
            </button>

            <a href="" id="deleteLink"
               class="confirm-btn">
                Hapus
            </a>
        </div>
    </div>
</div>
<script>
function openDeleteModal(id){
    document.getElementById("deleteModal").style.display = "flex";
    document.getElementById("deleteLink").href =
        "hapus.php?id=" + id;
}

function closeDeleteModal(){
    document.getElementById("deleteModal").style.display = "none";
}
</script>
</body>
</html>