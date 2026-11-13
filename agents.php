<?php
//Démarrer la session
session_start();

//Import des ressources
include './AbstractController.php';
include './model/AbstractModel.php';
include './model/ModelAgents.php';
include './model/ModelArchetypes.php';
include './model/ModelTiers.php';
include './view/View.php';
include './view/ViewHeader.php';
include './view/ViewAgents.php';

class ControllerAgent extends AbstractController{
    //ATTRIBUT

    //CONSTRUCTEUR

    //GETTER ET SETTER

    //METHODS
    public function isConnected():void{
        //Accessible uniquement si connecté
        if(!isset($_SESSION["id"])){
            //non connecté -> redirection vers accueil
            header('Location:index.php');
            exit;
        }
    }

    public function registerAgent():string{
        //1. Vérifier si on reçoit le formulaire
        if(isset($_POST["submit"])){
            //2. Normaliser les données
            $name = trim($_POST["name"]);
            $dob = trim($_POST["dob"]);
            $tier = trim($_POST["tier"]);
            $archetype = trim($_POST["archetype"]);

            //3. Vérifier les champs vides
            if(!empty($name) && !empty($dob) && !empty($tier) && !empty($archetype)){
                //4. Vérifier le format de donnée
                if(filter_var($tier,FILTER_VALIDATE_INT)){
                    if(filter_var($archetype,FILTER_VALIDATE_INT)){
                        
                        $data = $this->getModel('modelAgent')->setName($name)->findByName();

                        //5.5 Vérification si $data vide
                        if(empty($data)){
                            //6. Vérifier si l'archetype existe
                                //6.4 Récupération
                                $data = $this->getModel('modelArchetype')->setId($archetype)->findById();

                                //6.5 Vérification si $data vide
                                if(!empty($data)){
                                    //7. Vérifier si le tier existe
                                    $data = $this->getModel('modelTier')->setId($tier)->findById();

                                    //7.5 Vérification si $data vide
                                    if(!empty($data)){
                                        //8. Lancer l'enregistrement
                                        $this->getModel('modelAgent')->setDob($dob)->setTier($tier)->setArchetype($archetype)->add();

                                        //8.4 Message
                                        return htmlspecialchars($name)." a été enregistré.";

                                    }else{
                                        return "Ce Tier n'existe pas";
                                    }

                                }else{
                                    return "Cet Archetype n'existe pas";
                                }
                                
                            }else{
                                return "Ce nom n'est pas disponible.";
                            }
                        
                    }else{
                        return "Archetype est invalide";
                    }
                }else{
                    return "Le Tier est invalide";
                }
                
            }else{
                return "Remplissez tous les champs.";
            }
        }

        return "";
    }

    public function displayAgents():array | string{
        return $this->getModel('modelAgent')->findAll();
    }

    public function render():void{
        $this->isConnected();

        $this->getView('agent')->setMessage($this->registerAgent());

        $data = $this->displayAgents();

        if(gettype($data) === "array"){
            $this->getView('agent')->setListAgents($data);
        }else{
            $this->getView('agent')->setMessage($data);
        }

        $tiers = $this->getModel('modelTier')->findAll();
        $archetype = $this->getModel('modelArchetype')->findAll();

        $this->getView('header')->display();
        $this->getView('agent')->setTiers($tiers)->setArchetypes($archetype)->display();

    }

}

//Creation des Modèles
$pdo = new PDO("mysql:host=localhost;dbname=agent_manager","root","root");

$modelAgent = new ModelAgents($pdo);
$modelArchetype = new ModelArchetypes($pdo);
$modelTier = new ModelTiers($pdo);

//Création des Views
$header = new ViewHeader("Agents Of Shield");
$agent = new ViewAgent();

//Controller
$controller = new ControllerAgent(["modelAgent"=>$modelAgent, "modelTier"=>$modelTier,"modelArchetype"=>$modelArchetype],["header"=>$header,"agent"=>$agent]);

//Lancement du rendu
$controller->render();


