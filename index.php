<?php
//ACCUEIL
//Création d'un compte Manager
//Connexion d'un compte Manager
//Listing des Managers
//Affichage dynamique selon l'état de connexion

//Démarrage de la session
session_start();

//Import des ressources
include './AbstractController.php';
include './model/AbstractModel.php';
include './model/ModelUsers.php';
include './view/View.php';
include './view/ViewHeader.php';
include './view/ViewAccueil.php';

class ControllerAccueil extends AbstractController{
    //ATTRIBUT

    //CONSTRUCTEUR

    //GETTER ET SETTER

    //METHOD
    public function registerManager():string{
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
                        $data = $this->getModel('modelUser')->setEmail($email)->findByEmail();

                        if(empty($data)){
                            //7. Enregistrer le manager
                            return $this->getModel('modelUser')->setFirstname($firstname)->setLastname($lastname)->setPassword($password)->add();
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

    public function login():string{
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
                        $data = $this->getModel('modelUser')->setEmail($email)->findByEmail();
                            
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

    public function displayManagers():array{
        //LISTE DES MANAGERS
        //Vérifier si un manager est connecté
        if(isset($_SESSION["id"])){
            //Appel du modèle pour récupérer les manager
            return $this->getModel('modelUser')->findAll();
        }

        return [];
    }

    public function render():void{
        $this->getView('accueil')->setMessage($this->registerManager());
        $this->getView('accueil')->setMessageConnection($this->login());
        $data = $this->displayManagers();

        //Vérification du type de data
        if(gettype($data) === "array"){
            $this->getView('accueil')->setListManagers($data);
        }else{
            $this->getView('accueil')->setMessageError($data);
        }

        $this->getView('header')->display();
        $this->getView('accueil')->display();
    }

}

//Creation du model
$model = new ModelUsers(new PDO("mysql:host=localhost;dbname=agent_manager","root","root"));

//Creation des View
$header = new ViewHeader("Accueil");
$accueil = new ViewAccueil();

//Instaciation du controler
$controller = new ControllerAccueil(['modelUser' => $model],['header' => $header,"accueil" => $accueil]);

//Lancement du rendu
$controller->render();


