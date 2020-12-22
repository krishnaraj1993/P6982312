<?php
session_start();
include 'include/config.php';
$user = $_SESSION['id'];
$product = $_GET['product'];
$price = $_GET['price'];

$servername = "localhost";
$username = "admin";
$password = "Knp@9845409904";
$dbname = "shopping";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    return 0;
}
sendMail($_SESSION['login']);die;
if (strlen($_SESSION['login']) == 0) {
    echo "Please Login";
    return 0;
}
$sql = "INSERT INTO priceAnalysis(product, currentPrice, user) VALUES ($product,'$price',$user)";
if ($conn->query($sql) === true) {
    echo "Email alert has been updated ";
    sendMail($_SESSION['login']);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

function sendMail($email)
{
    $to = $email;
    $subject = "Confirmation";

    $message = "
<html>
<head>
<title>Confirmation</title>
</head>
<body>
<p>Confirmation : Price alert updated</p>
</body>
</html>
";

// Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: <webmaster@example.com>' . "\r\n";
    $headers .= 'Cc: myboss@example.com' . "\r\n";

    mail($to, $subject, $message, $headers);
}