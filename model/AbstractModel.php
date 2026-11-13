<?php
abstract class AbstractModel{
    //ATTRIBUT
    private PDO $pdo;

    //CONSTRUCTEUR
    public function __construct(PDO $newPDO){
        $this->pdo = $newPDO;
    }

    //GETTER ET SETTER
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
    public abstract function findAll():array | string;
}