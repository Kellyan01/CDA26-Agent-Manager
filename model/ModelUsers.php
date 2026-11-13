<?php
class ModelUsers extends AbstractModel{
    //ATTRIBUT
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private string $createdAt;
    private int $role;

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
     * Get the value of firstname
     *
     * @return string
     */
    public function getFirstname(): string {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param string $firstname
     *
     * @return self
     */
    public function setFirstname(string $firstname): self {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get the value of lastname
     *
     * @return string
     */
    public function getLastname(): string {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param string $lastname
     *
     * @return self
     */
    public function setLastname(string $lastname): self {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get the value of email
     *
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of password
     *
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self {
        $this->password = $password;
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
     * Get the value of role
     *
     * @return string
     */
    public function getRole(): string {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param string $role
     *
     * @return self
     */
    public function setRole(string $role): self {
        $this->role = $role;
        return $this;
    }


    //METHOD
    public function findAll():array | string{
        try{
            //A.Préparer la requête
            $request = $this->getPdo()->prepare("SELECT u.Id_User, u.firstname, u.lastname, u.email, u.password, u.created_at, r.role FROM users u INNER JOIN roles r ON u.Id_Role = r.Id_Role WHERE r.role = 'Manager'");

            //B.Exécuter la requête
            $request->execute();

            //C.Récupérer les données
            return $request->fetchAll(PDO::FETCH_ASSOC);
                            
        }catch(Exception $error){
            http_response_code(500);
            return $error->getMessage();

            //Erreur 500 : Serveur indisponible
        }
    }

    public function findByEmail():array | string{
        try{
            //A. Préparer la requête de lecture des données lié à l'email
            $request = $this->getPdo()->prepare('SELECT u.Id_User, u.firstname, u.lastname, u.email, u.password, u.created_at, r.role FROM users u INNER JOIN roles r ON u.Id_Role = r.Id_Role WHERE u.email = ?');

            //B. Relier le ? à mon email
            $request->bindParam(1,$this->email,PDO::PARAM_STR);

            //C. Exécuter la requête
            $request->execute();

            //D. Récupérer les données
            return $request->fetch(PDO::FETCH_ASSOC);

        }catch(Exception $error){
            http_response_code(500);
            return $error->getMessage();
            //Erreur 500 : Serveur indisponible
        }
    }

    public function add():string{
        try{
            //A. Préparer la requête de lecture des données lié à l'email
            $request = $this->getPdo()->prepare('INSERT INTO users (firstname, lastname, email, `password`, Id_Role) VALUES(?,?,?,?,2)');

            //B. Relier les ? à mes données
            $request->bindParam(1,$this->firstname,PDO::PARAM_STR);
            $request->bindParam(2,$this->lastname,PDO::PARAM_STR);
            $request->bindParam(3,$this->email,PDO::PARAM_STR);
            $request->bindParam(4,$this->password,PDO::PARAM_STR);

            //C. Exécuter la requête
            $request->execute();
            
            //D. Retourner un message
            return $this->firstname." a été enregistré.";

        }catch(Exception $error){
            http_response_code(500);
            return $error->getMessage();
            //Erreur 500 : Serveur indisponible
        }
    }  
}
