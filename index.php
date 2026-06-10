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

<h2>Selamat datang <?= $_SESSION['name']; ?></h2>

<a href="logout.php">Logout</a>

<table border="1">
<tr>
    <th>ID</th>
    <th>Judul</th>
    <th>Tahun</th>
</tr>

<?php foreach($movies as $movie): ?>

<tr>
    <td><?= $movie['id'] ?></td>
    <td><?= $movie['title'] ?></td>
    <td><?= $movie['year'] ?></td>
</tr>

<?php endforeach; ?>

</table>