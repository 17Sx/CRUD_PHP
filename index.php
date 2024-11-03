<!DOCTYPE html>
<html lang="fr">
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

if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pseudo = $_SESSION['pseudo'];
$userId = $_SESSION['user_id'];
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] == 1;
?>

<div class="w-full max-w-3xl px-6 py-8 bg-black bg-opacity-50 rounded-2xl shadow-lg m-10 border">
    <nav class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white">Whatassap</h1>
        <a href="logout.php" class="bg-purple-600 px-4 py-2 rounded-lg font-semibold text-white">Déconnexion</a>
    </nav>

    <div class="bg-black bg-opacity-40 rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold mb-4 text-white">Créer un nouveau message</h2>
        <form action="create.php" method="POST" class="flex flex-col space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <textarea name="content" placeholder="Écrivez votre message ici, <?php echo htmlspecialchars($pseudo); ?>" 
                    class="w-full p-4 bg-transparent border-b-2 border-white placeholder-gray-400 text-white focus:outline-none focus:placeholder-transparent" 
                    required></textarea>
            <button type="submit" class="w-full p-3 bg-purple-600 rounded-lg text-white font-semibold transition-transform duration-200 hover:scale-105">Envoyer le message</button>
        </form>
    </div>

    <div>
        <h2 class="text-2xl font-semibold text-gray-300 mb-4">Messages récents</h2>
        <div id="messages" class="space-y-4">
            <?php
            $dsn = 'mysql:host=localhost;dbname=renduphpcrud;charset=utf8';
            $username = 'root';
            $password = '';

            try {
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->query("
                    SELECT user.pseudo, message.content, message.creea, message.id AS message_id, message.user_id
                    FROM message
                    JOIN user ON message.user_id = user.id
                    ORDER BY message.creea DESC
                    LIMIT 10
                ");

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $isOwnMessage = $row['user_id'] == $userId;
                    $messageClass = $isOwnMessage ? 'border border-red-800 shadow-lg' : 'bg-gray-800 border border-gray-600 shadow-lg';
                    echo '<div class="' . $messageClass . ' bg-opacity-60 p-4 rounded-lg shadow-md animate-slide-in">';
                    echo '<p class="text-sm text-gray-400">' . htmlspecialchars($row['pseudo']) . ' - ' . htmlspecialchars($row['creea']) . '</p>';
                    echo '<p class="text-lg text-white">' . htmlspecialchars($row['content']) . '</p>';

                    if ($isAdmin) {
                        echo '<div class="flex space-x-4 mt-2">';
                        echo '<a href="edit.php?id=' . $row['message_id'] . '" class="text-yellow-400 hover:text-yellow-500">Modifier</a>';
                        echo '<a href="delete.php?id=' . $row['message_id'] . '" class="text-red-500 hover:text-red-600">Supprimer</a>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
            ?>
        </div>
    </div>
</div>

<style>
body {
    display: flex;
    justify-content: center;
    align-items: center;    
    min-height: 100vh;
    background: rgb(2,0,36);
    background: linear-gradient(183deg, rgba(2,0,36,1) 0%, rgba(152,56,149,1) 0%, rgba(4,0,106,1) 100%);
    background-size: cover;
    background-repeat: no-repeat;
}

@keyframes slide-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>
</body>
</html>
