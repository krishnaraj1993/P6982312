<?php
session_start();
include('admin/include/config.php');
$user = $_SESSION['id'];
$product = $_GET['product'];
$price = $_GET['price'];

if (strlen($_SESSION['login']) == 0) {
    echo "Please Login";
    return 0;
}
$sql = "INSERT INTO priceAnalysis(product, currentPrice, user) VALUES ($product,'$price',$user)";
if (mysqli_query($con,$sql)) {
    echo "Email alert has been updated";
    sendMail($_SESSION['login']);
} else {
    echo "Error: ";
}

function sendMail($email)
{
    $to = $email;
    $cURLConnection = curl_init();

    curl_setopt($cURLConnection, CURLOPT_URL, 'http://127.0.0.1:5000/confirmation/email?id='.$to);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($cURLConnection);
    curl_close($cURLConnection);
    
    return json_decode($response);
}