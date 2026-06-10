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

if($hasil && $hasil['status'] == "success"){

    header("Location: index.php");
    exit();

}else{

    echo "
    <script>
        alert('Gagal menghapus movie');
        window.location='index.php';
    </script>
    ";

}
?>
