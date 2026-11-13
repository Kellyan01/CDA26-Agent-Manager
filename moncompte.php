<?php
//Affichage des informations utilisateurs
//Démarrer la session
session_start();

//Import des ressources
include './view/ViewHeader.php';
include './view/ViewCompte.php';

class ControllerCompte{
    //ATTRIBUT
    private ViewHeader $header;
    private ViewCompte $compte;

    //CONSTRUCTEUR
    public function __construct(ViewHeader $header, ViewCompte $compte){
        $this->header = $header;
        $this->compte = $compte;
    }

    //GETTER ET SETTER
    /**
     * Get the value of header
     *
     * @return ViewHeader
     */
    public function getHeader(): ViewHeader {
        return $this->header;
    }

    /**
     * Set the value of header
     *
     * @param ViewHeader $header
     *
     * @return self
     */
    public function setHeader(ViewHeader $header): self {
        $this->header = $header;
        return $this;
    }

    /**
     * Get the value of compte
     *
     * @return ViewCompte
     */
    public function getCompte(): ViewCompte {
        return $this->compte;
    }

    /**
     * Set the value of compte
     *
     * @param ViewCompte $compte
     *
     * @return self
     */
    public function setCompte(ViewCompte $compte): self {
        $this->compte = $compte;
        return $this;
    }

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
        $this->header->displayHeader();
        $this->compte->display();
    }
}

//Création des View
$header = new ViewHeader("Mon Compte");
$compte = new ViewCompte();

//Création du controller
$controller = new ControllerCompte($header, $compte);

//Lancer le rendu
$controller->render();


