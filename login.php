<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if(isset($_SESSION['login'])){
    header("Location: dashboard.php");
    exit();
}

$message = "";

if(isset($_POST['login'])){

    $data = [
        "email" => $_POST['email'],
        "password" => $_POST['password']
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://localhost/UTSAPI/api/login_api.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    $result = json_decode($response, true);

    if($result['status']){

        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $result['user']['id'];
        $_SESSION['name'] = $result['user']['name'];
        $_SESSION['api_key'] = $result['user']['api_key'];

        header("Location: dashboard.php");
        exit();

    }else{

        $message = $result['message'];

    }
}
?>