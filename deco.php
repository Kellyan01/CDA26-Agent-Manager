<?php
//Déconnexion d'un utilisateur
//Démarrer la session
session_start();

//Détruire la session
session_destroy();

//Rediriger vers l'accueil
header('Location:index.php');
exit;