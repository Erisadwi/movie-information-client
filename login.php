<?php
session_start();
include "config.php";

$message = "";

if(isset($_POST['login'])){

    $data = [
        "email"=>$_POST['email'],
        "password"=>$_POST['password']
    ];

    $curl = curl_init();

    curl_setopt_array($curl,[
        CURLOPT_URL=>$base_url."login.php",
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_POST=>true,
        CURLOPT_POSTFIELDS=>json_encode($data),
        CURLOPT_HTTPHEADER=>[
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response,true);

    if($response['status']){

        $_SESSION['login']=true;
        $_SESSION['name']=$response['name'];
        $_SESSION['api_key']=$response['api_key'];

        header("Location:dashboard.php");
        exit();

    }else{
        $message = $response['message'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - Movie Client</title>
<link rel="stylesheet" href="css/style_login.css">
</head>
<body>

<div class="card">
    <h1>🎥 MOVIE CLIENT</h1>
    <h3>Login</h3>
    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="Email"
            required>

        <input
            type="password"
            name="password"
            placeholder="Password"
            required>

        <button name="login">
            Login
        </button>

    </form>
    <div class="msg">
        <?= $message ?>
    </div>
</div>
</body>
</html>