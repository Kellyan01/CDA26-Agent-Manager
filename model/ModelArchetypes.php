<?php
class ModelArchetypes {
    //ATTRIBUT
    private int $id;
    private string $archetype;
    private PDO $pdo;

    //CONSTRUCTEUR
    public function __construct(PDO $newPDO){
        $this->pdo = $newPDO;
    }

    //GETTER ET SETTER
    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of archetype
     *
     * @return string
     */
    public function getArchetype(): string {
        return $this->archetype;
    }

    /**
     * Set the value of archetype
     *
     * @param string $archetype
     *
     * @return self
     */
    public function setArchetype(string $archetype): self {
        $this->archetype = $archetype;
        return $this;
    }

    /**
     * Get the value of pdo
     *
     * @return PDO
     */
    public function getPdo(): PDO {
        return $this->pdo;
    }

    /**
     * Set the value of pdo
     *
     * @param PDO $pdo
     *
     * @return self
     */
    public function setPdo(PDO $pdo): self {
        $this->pdo = $pdo;
        return $this;
    }

    //METHOD
    public function findAll():array | string{
        try{
            
            //Préparation de la requêt pour Archetype
            $request = $this->pdo->prepare("SELECT a.Id_Archetype, a.archetype FROM archetypes a");
            //Execution
            $request->execute();
            //Récupération
            return $request->fetchAll(PDO::FETCH_ASSOC);

        }catch(Exception $error){
            http_response_code(500);
            return $error->getMessage();
        }
    }

    public function findById():array | string{
        try{
            
            //Préparation de la requêt pour Archetype
            $request = $this->pdo->prepare("SELECT a.Id_Archetype, a.archetype FROM archetypes a WHERE a.Id_Archetype = ?");
            //BindParam
            $request->bindParam(1,$this->id,PDO::PARAM_INT);
            //Execution
            $request->execute();
            //Récupération
            return $request->fetch(PDO::FETCH_ASSOC);

        }catch(Exception $error){
            http_response_code(500);
            return $error->getMessage();
        }
    }
}