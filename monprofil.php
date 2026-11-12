<?php
//Affichage des informations utilisateurs
//Démarrer la session
session_start();

//Variable d'affichage
$title = "Mon Compte";

//Accessible uniquement si connecté
if(!isset($_SESSION["id"])){
    //non connecté -> redirection vers accueil
    header('Location:index.php');
    exit;
}

include './header.php';
?>

    <main>
        <h2>Mon Compte</h2>
        <p><?php echo(htmlspecialchars($_SESSION["firstname"])." ".htmlspecialchars($_SESSION["lastname"])) ?></p>
        <p>Email : <?php echo(htmlspecialchars($_SESSION["email"])) ?></p>
        <p>Role : <?php echo(htmlspecialchars($_SESSION["role"])) ?></p>
        <p>Date d'enregistrement : <?php echo(htmlspecialchars($_SESSION["createdAt"])) ?></p>
    </main>
    
</body>
</html>
