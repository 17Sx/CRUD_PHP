<?php
session_start();

if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo "non non ";
        exit();
    }

    if (!empty($_POST['content'])) {
        $content = $_POST['content'];

        require 'bdd.php';


        $stmt = $connexion->prepare("INSERT INTO message (user_id, content) VALUES (:user_id, :content)");

        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':content', $content);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "non";
        }
    }
}
?>