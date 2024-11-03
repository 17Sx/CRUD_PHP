<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whatassap</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font.css">
</head>
<body>

<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['admin'] != 1) {
    echo '<div class="text-white">Accès refusé.</div>';
    exit();
}

    if (isset($_GET['id'])) {
        $message_id = $_GET['id'];
        $dsn = 'mysql:host=localhost;dbname=renduphpcrud;charset=utf8';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT content FROM message WHERE id = :id");
            $stmt->bindParam(':id', $message_id);
            $stmt->execute();
            $message = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($message) {
            echo '<div class="bg-black bg-opacity-40 rounded-lg p-6 mb-6">';
            echo '<h2 class="text-lg font-bold text-gray-200 mb-4">Modifier le message</h2>';
            echo '<form action="update.php" method="POST">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($message_id) . '">';
            echo '<textarea name="content" class="w-full p-3 bg-black bg-opacity-40 text-white rounded focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5">' . htmlspecialchars($message['content']) . '</textarea>';
            echo '<button type="submit" class="mt-4 bg-purple-600 px-4 py-2 rounded text-white hover:bg-blue-700 transition duration-300">Mettre à jour</button>';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<div class="text-white">no msg</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="text-white">Erreur : ' . $e->getMessage() . '</div>';
    }
} else {
    echo '<div class="text-white">noid</div>';
}
?>

<style>
        body{
    display: flex;
    justify-content: center;
    align-items: center;    
    height: 100vh;
    
    background: rgb(2,0,36);
    background: linear-gradient(183deg, rgba(2,0,36,1) 0%, rgba(152,56,149,1) 0%, rgba(4,0,106,1) 100%);
}
</style>

</body>
</html>
