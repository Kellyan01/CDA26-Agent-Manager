<?php
//Affichage des informations utilisateurs
//Démarrer la session
session_start();

//Import des ressources
include './AbstractController.php';
include './view/View.php';
include './view/ViewHeader.php';
include './view/ViewCompte.php';

class ControllerCompte extends AbstractController{
    //ATTRIBUT

    //CONSTRUCTEUR

    //GETTER ET SETTER

    //METHOD
    public function isConnect():void{
        //Accessible uniquement si connecté
        if(!isset($_SESSION["id"])){
        //non connecté -> redirection vers accueil
        header('Location:index.php');
        exit;
    }

    }
    public function render():void{
        $this->isConnect();
        $this->getView('header')->display();
        $this->getView('compte')->display();
    }
}

//Création des View
$header = new ViewHeader("Mon Compte");
$compte = new ViewCompte();

//Création du controller
$controller = new ControllerCompte([],['header'=>$header, 'compte'=>$compte]);

//Lancer le rendu
$controller->render();


