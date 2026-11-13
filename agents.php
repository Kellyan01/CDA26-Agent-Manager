<?php
//Démarrer la session
session_start();

//Import des ressources
include './model/ModelAgents.php';
include './model/ModelArchetypes.php';
include './model/ModelTiers.php';
include './view/ViewHeader.php';
include './view/ViewAgents.php';

class ControllerAgent{
    //ATTRIBUT
    private ModelAgents $modelAgent;
    private ModelTiers $modelTier;
    private ModelArchetypes $modelArchetype;
    private ViewHeader $header;
    private ViewAgent $agent;

    //CONSTRUCTEUR
    public function __construct(ModelAgents $modelAgent, ModelTiers $modelTier, ModelArchetypes $modelArchetype, ViewHeader $header, ViewAgent $agent){
        $this->modelAgent = $modelAgent;
        $this->modelTier = $modelTier;
        $this->modelArchetype = $modelArchetype;
        $this->header = $header;
        $this->agent = $agent;
    }

    //GETTER ET SETTER
    /**
     * Get the value of modelAgent
     *
     * @return ModelAgents
     */
    public function getModelAgent(): ModelAgents {
        return $this->modelAgent;
    }

    /**
     * Set the value of modelAgent
     *
     * @param ModelAgents $modelAgent
     *
     * @return self
     */
    public function setModelAgent(ModelAgents $modelAgent): self {
        $this->modelAgent = $modelAgent;
        return $this;
    }

    /**
     * Get the value of modelTier
     *
     * @return ModelTiers
     */
    public function getModelTier(): ModelTiers {
        return $this->modelTier;
    }

    /**
     * Set the value of modelTier
     *
     * @param ModelTiers $modelTier
     *
     * @return self
     */
    public function setModelTier(ModelTiers $modelTier): self {
        $this->modelTier = $modelTier;
        return $this;
    }

    /**
     * Get the value of modelArchetype
     *
     * @return ModelArchetypes
     */
    public function getModelArchetype(): ModelArchetypes {
        return $this->modelArchetype;
    }

    /**
     * Set the value of modelArchetype
     *
     * @param ModelArchetypes $modelArchetype
     *
     * @return self
     */
    public function setModelArchetype(ModelArchetypes $modelArchetype): self {
        $this->modelArchetype = $modelArchetype;
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
     * Get the value of agent
     *
     * @return ViewAgent
     */
    public function getAgent(): ViewAgent {
        return $this->agent;
    }

    /**
     * Set the value of agent
     *
     * @param ViewAgent $agent
     *
     * @return self
     */
    public function setAgent(ViewAgent $agent): self {
        $this->agent = $agent;
        return $this;
    }

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
                        
                        $data = $this->modelAgent->setName($name)->findByName();

                        //5.5 Vérification si $data vide
                        if(empty($data)){
                            //6. Vérifier si l'archetype existe
                                //6.4 Récupération
                                $data = $this->modelArchetype->setId($archetype)->findById();

                                //6.5 Vérification si $data vide
                                if(!empty($data)){
                                    //7. Vérifier si le tier existe
                                    $data = $this->modelTier->setId($tier)->findById();

                                    //7.5 Vérification si $data vide
                                    if(!empty($data)){
                                        //8. Lancer l'enregistrement
                                        $this->modelAgent->setDob($dob)->setTier($tier)->setArchetype($archetype)->add();

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
        return $this->modelAgent->findAll();
    }

    public function render():void{
        $this->isConnected();

        $this->agent->setMessage($this->registerAgent());

        $data = $this->displayAgents();

        if(gettype($data) === "array"){
            $this->agent->setListAgents($data);
        }else{
            $this->agent->setMessage($data);
        }

        $tiers = $this->getModelTier()->findAll();
        $archetype = $this->getModelArchetype()->findAll();

        $this->header->displayHeader();
        $this->agent->setTiers($tiers)->setArchetypes($archetype)->display();

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
$controller = new ControllerAgent($modelAgent,$modelTier,$modelArchetype,$header,$agent);

//Lancement du rendu
$controller->render();


