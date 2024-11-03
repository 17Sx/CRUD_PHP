<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font.css">
</head>
<body>

<div class="flex justify-center items-center w-full h-full bg-black bg-opacity-30">
    <div class="flex flex-col justify-center items-center w-1/4 h-3/4 bg-black bg-opacity-50 rounded-2xl p-8">
        <h1 class="text-3xl font-bold text-white mb-8">CONNEXION</h1>
        <form action="login.php" method="POST" class="flex flex-col items-center w-full">
            <label for="pseudo" class="text-white text-lg mb-2">Nom d'utilisateur</label>
            <input type="text" name="pseudo" id="pseudo" placeholder="17sx" required class="w-full p-3 mb-6 bg-transparent border-b-2 border-white placeholder-white/20 text-white focus:outline-none focus:placeholder-transparent">
            
            <label for="password" class="text-white text-lg mb-2">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="123password" required class="w-full p-3 mb-6 bg-transparent border-b-2 border-white placeholder-white/20 text-white focus:outline-none focus:placeholder-transparent">

            <button type="submit" class="w-full p-3 bg-black bg-opacity-50 text-white font-bold transition-transform duration-200 hover:scale-110">Se connecter</button>
        </form>

        <a href="register.php" class="mt-8 text-white text-lg font-bold hover:text-pink-500 transition duration-200">Pas de compte ?</a>
    </div>
</div>

<?php
session_start();
require_once 'bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['pseudo']) && !empty($_POST['password'])) {
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];

        $stmt = $connexion->prepare("SELECT id, pseudo, password, admin FROM user WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['admin'] = $user['admin'];

            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert Mauvais identifiants</script>";
        }
    } else {
        $error = "remplir tout les champs";
    }
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
