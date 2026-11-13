<?php
//ACCUEIL
//Création d'un compte Manager
//Connexion d'un compte Manager
//Listing des Managers
//Affichage dynamique selon l'état de connexion

//Démarrage de la session
session_start();

//Import des ressources
include './model/ModelUsers.php';
include './view/ViewHeader.php';
include './view/ViewAccueil.php';

class ControllerAccueil{
    //ATTRIBUT
    private ModelUsers $modelUser;
    private ViewHeader $header;
    private ViewAccueil $accueil;

    //CONSTRUCTEUR
    public function __construct(ModelUsers $modelUser, ViewHeader $header, ViewAccueil $accueil){
        $this->modelUser = $modelUser;
        $this->header = $header;
        $this->accueil = $accueil;
    }

    //GETTER ET SETTER
    /**
     * Get the value of modelUser
     *
     * @return ModelUser
     */
    public function getModelUser(): ModelUser {
        return $this->modelUser;
    }

    /**
     * Set the value of modelUser
     *
     * @param ModelUser $modelUser
     *
     * @return self
     */
    public function setModelUser(ModelUser $modelUser): self {
        $this->modelUser = $modelUser;
        return $this;
    }

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
     * Get the value of accueil
     *
     * @return ViewAccueil
     */
    public function getAccueil(): ViewAccueil {
        return $this->accueil;
    }

    /**
     * Set the value of accueil
     *
     * @param ViewAccueil $accueil
     *
     * @return self
     */
    public function setAccueil(ViewAccueil $accueil): self {
        $this->accueil = $accueil;
        return $this;
    }

    //METHOD
    public function registerManager(){
        //INSCRIPTION MANAGER
        //1. Vérifier la réception du formulaire d'inscription
        if(isset($_POST['submit'])){

            //2. Normaliser les données avec trim : firstname, lastname, email
            // On enlève les espaces avantr de vérifier les champs vide. Ainsi, si un champ obligatoire est constitué uniquement d'espace, alors il ne passera pas la vérification des champs vide. Par contre, on ne trim jamais le mot de passe, car espace peut en faire légitimement partie
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST["lastname"]);
            $email = trim($_POST["email"]);

            //3. Vérifier les champs vide
            if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($_POST["password"])){

                //4. Vérifier le format des données
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    if(mb_strlen($_POST["password"]) >= 12){
                        //5. Hasher le mot de passe
                        $password = password_hash($_POST["password"],PASSWORD_DEFAULT);

                        //6. Vérifier si l'email est disponible
                        $data = $this->modelUser->setEmail($email)->findByEmail();

                        if(empty($data)){
                            //7. Enregistrer le manager
                            return $this->modelUser->setFirstname($firstname)->setLastname($lastname)->setPassword($password)->add();
                        }else{
                            return "Email indisponible.";
                        }
                    }else{
                        return "le mot de passe doit faire au moins 12 caractères.";
                    }
                }else{
                    return "l'email n'est pas au bon format.";
                }
            }else{
                return "Veuillez remplir tous les champs.";
            }
        }

        return "";
    }

    public function login(){
        //CONNEXION MANAGER
        //1. Vérifier la réception du formulaire d'inscription
        if(isset($_POST["submitConnection"])){
            //2. Normaliser les données avec trim : email
            $email = trim($_POST["emailConnection"]);

            //3. Vérifier les champs vide
            if(!empty($email) && !empty($_POST["passwordConnection"])){
                //4. Vérifier le format des données
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    
                    if(mb_strlen($_POST["passwordConnection"]) >= 12){
                        $data = $this->modelUser->setEmail($email)->findByEmail();
                            
                        //E. Vérifier si $data est rempli -> le compte existe
                        if(!empty($data)){
                            //Vérifier le type de data. Si c'est un tableau, c'est mon tableau utilisateur. Sinon, c'est uen chaîne de caractère, et c'est donc le message d'erreur envoyé par le catch.
                            if(gettype($data) == "array"){
                                //6. Vérifier les mots de passe
                                if(password_verify($_POST["passwordConnection"], $data["password"])){
                                    //7. Connecter le manager
                                    $_SESSION["id"] = $data["Id_User"];
                                    $_SESSION["firstname"] = $data["firstname"];
                                    $_SESSION["lastname"] = $data["lastname"];
                                    $_SESSION["email"] = $data["email"];
                                    $_SESSION["createdAt"] = $data["created_at"];
                                    $_SESSION["role"] = $data["role"];

                                    return htmlspecialchars($_SESSION["firstname"])." est connecté";

                                }else{
                                    return "L'email et/ou le mot de passe est invalide.";
                                }
                            }else{
                                return $data;
                            }
                                
                        }else{
                            return "L'email et/ou le mot de passe est invalide.";
                        }
                        
                    }else{
                        return "Mot de passe doit faire au moins 12 caractères";
                    }
                    
                }else{
                    return "Format d'email invalide";
                }
                

            }else{
                return "Tous les champs sont obligatoires.";
            }
            
        }

        return "";
    }

    public function displayManagers(){
        //LISTE DES MANAGERS
        //Vérifier si un manager est connecté
        if(isset($_SESSION["id"])){
            //Appel du modèle pour récupérer les manager
            return $this->modelUser->findAll();
        }

        return [];
    }

    public function render(){
        $this->accueil->setMessage($this->registerManager());
        $this->accueil->setMessageConnection($this->login());
        $data = $this->displayManagers();

        //Vérification du type de data
        if(gettype($data) === "array"){
            $this->accueil->setListManagers($data);
        }else{
            $this->accueil->setMessageError($data);
        }

        $this->header->displayHeader();
        $this->accueil->display();
    }

}

//Creation du model
$model = new ModelUsers(new PDO("mysql:host=localhost;dbname=agent_manager","root","root"));

//Creation des View
$header = new ViewHeader("Accueil");
$accueil = new ViewAccueil();

//Instaciation du controler
$controller = new ControllerAccueil($model,$header,$accueil);

//Lancement du rendu
$controller->render();


