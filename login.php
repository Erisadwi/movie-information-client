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

        header("Location:index.php");
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

<style>
body{
    margin:0;
    font-family:Arial;
    background:linear-gradient(120deg,#141e30,#243b55);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    background:white;
    padding:40px;
    width:350px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    text-align:center;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    background:#243b55;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#141e30;
}

.msg{
    margin-top:15px;
    color:red;
}
</style>

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
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

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