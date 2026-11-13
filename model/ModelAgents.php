<?php
class ModelAgents extends AbstractModel{
    //ATTRIBUT
    private int $id;
    private string $name;
    private string $dob;
    private string $createdAt;
    private string $tier;
    private string $archetype;
    private string $team;

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
     * Get the value of name
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of dob
     *
     * @return string
     */
    public function getDob(): string {
        return $this->dob;
    }

    /**
     * Set the value of dob
     *
     * @param string $dob
     *
     * @return self
     */
    public function setDob(string $dob): self {
        $this->dob = $dob;
        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return string
     */
    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param string $createdAt
     *
     * @return self
     */
    public function setCreatedAt(string $createdAt): self {
        $this->createdAt = $createdAt;
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
     * Get the value of team
     *
     * @return string
     */
    public function getTeam(): string {
        return $this->team;
    }

    /**
     * Set the value of team
     *
     * @param string $team
     *
     * @return self
     */
    public function setTeam(string $team): self {
        $this->team = $team;
        return $this;
    }

    //METHOD
    public function findAll():array | string {
        try{
            //Préparation de la requête
            $request = $this->getPdo()->prepare("SELECT a.Id_Agent, a.name, a.dob, a.created_at, t.tier, ac.archetype, te.team_name FROM agents a INNER JOIN tiers t ON t.Id_Tier = a.Id_Tier INNER JOIN archetypes ac ON ac.Id_Archetype = a.Id_Archetype LEFT JOIN Teams te ON te.Id_Team = a.Id_Team");

            //Execution
            $request->execute();

            //Récupère
            return $request->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $error){
            http_response_code(500);
            return $error->getMessage();
        }
    }

    public function findByName():array | string{
        try{
            //5.1 Préparation de la requête
            $request = $this->getPdo()->prepare("SELECT a.Id_Agent, a.name, a.dob, a.created_at, t.tier, ac.archetype, te.team_name FROM agents a LEFT JOIN teams te ON a.Id_Team = te.Id_Team INNER JOIN tiers t ON a.Id_Tier = t.Id_Tier INNER JOIN archetypes ac ON a.Id_Archetype = ac.Id_Archetype WHERE a.name = ?");

            //5.2 BindParam
            $request->bindParam(1,$this->name,PDO::PARAM_STR);

            //5.3 Exécution
            $request->execute();

            //5.4 Récupération
            return $request->fetch(PDO::FETCH_ASSOC);
        }catch(Exeception $error){
            return $error->getMessage();
        }
    }

    public function add():?string{
        try{
            //8.1 Préparation de la requête
            $request = $this->getPdo()->prepare("INSERT INTO agents (`name`,dob,Id_Tier, Id_Archetype) VALUES (?,?,?,?)");

            //8.2 BindParam
            $request->bindParam(1,$this->name,PDO::PARAM_STR);
            $request->bindParam(2,$this->dob,PDO::PARAM_STR);
            $request->bindParam(3,$this->tier,PDO::PARAM_INT);
            $request->bindParam(4,$this->archetype,PDO::PARAM_INT);

            //8.3 Execution
            $request->execute();

            return null;
        }catch(Exception $error){
            return $error->getMessage();
        }
    }
}