<?php
class ModelTiers extends AbstractModel{
    //ATTRIBUT
    private int $id;
    private string $tier;

    //CONSTRUCTEUR

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
     * Get the value of tier
     *
     * @return string
     */
    public function getTier(): string {
        return $this->tier;
    }

    /**
     * Set the value of tier
     *
     * @param string $tier
     *
     * @return self
     */
    public function setTier(string $tier): self {
        $this->tier = $tier;
        return $this;
    }

    //METHOD
    public function findAll():array | string{
        try{
            
            //Préparation de la requêt pour Tier
            $request = $this->getPdo()->prepare("SELECT t.Id_Tier, t.tier FROM tiers t");
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
            $request = $this->getPdo()->prepare("SELECT t.tier FROM tiers t WHERE t.Id_Tier = ?");
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