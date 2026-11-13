<?php
class ViewAccueil{
    //ATTRIBUT
    private string $message = "";
    private string $messageConnection = "";
    private string $messageError = "";
    private array $listManagers = [];

    //CONSTRUCTEUR

    //GETTER ET SETTER
    /**
     * Get the value of message
     *
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self {
        $this->message = $message;
        return $this;
    }

    /**
     * Get the value of messageConnection
     *
     * @return string
     */
    public function getMessageConnection(): string {
        return $this->messageConnection;
    }

    /**
     * Set the value of messageConnection
     *
     * @param string $messageConnection
     *
     * @return self
     */
    public function setMessageConnection(string $messageConnection): self {
        $this->messageConnection = $messageConnection;
        return $this;
    }

    /**
     * Get the value of messageError
     *
     * @return string
     */
    public function getMessageError(): string {
        return $this->messageError;
    }

    /**
     * Set the value of messageError
     *
     * @param string $messageError
     *
     * @return self
     */
    public function setMessageError(string $messageError): self {
        $this->messageError = $messageError;
        return $this;
    }

    /**
     * Get the value of listManagers
     *
     * @return array
     */
    public function getListManagers(): array {
        return $this->listManagers;
    }

    /**
     * Set the value of listManagers
     *
     * @param array $listManagers
     *
     * @return self
     */
    public function setListManagers(array $listManagers): self {
        $this->listManagers = $listManagers;
        return $this;
    }

    //METHOD
    public function display(){
    ?>
        <main>
                <h1>Accueil</h1>
                <?php
                    //Vérifier si personne n'est connecté
                    if(!isset($_SESSION["id"])){
                ?>
                <section>
                    <h2>Inscription</h2>
                    <form action="" method="post">
                        <label for="firstname">Prenom :<input id="firstname" type="text" name="firstname"></label>
                        <label for="lastname">Nom :<input id="lastname" type="text" name="lastname"></label>
                        <label for="email">Email :<input id="email" type="text" name="email"></label>
                        <label for="password">Motde Passe :<input id="password" type="text" name="password"></label>
                        <input type="submit" name="submit">
                    </form>
                    <p><?php echo $this->message ?></p>
                </section>
                <section>
                    <h2>Connexion</h2>
                    <form action="" method="post">
                        <label for="emailConnection">Email :<input id="emailConnection" type="text" name="emailConnection"></label>
                        <label for="passwordConnection">Motde Passe :<input id="passwordConnection" type="text" name="passwordConnection"></label>
                        <input type="submit" name="submitConnection">
                    </form>
                    <p><?php echo $this->messageConnection ?></p>
                </section>
                <?php
                    };
                    
                    //Vérifier si un manager est connecté
                    if(isset($_SESSION["id"])){
                ?>
                        <section>
                            <h2>Liste des Managers</h2>
                            <ul>
                                <?php
                                    foreach($this->listManagers as $manager){
                                ?>
                                        <li><?php 
                                        //Affichage des données avec nettoyage du code malveillant
                                        echo(htmlspecialchars($manager['firstname'])." ".htmlspecialchars($manager['lastname'])." - ".htmlspecialchars($manager['email'])) ?></li>
                                <?php
                                    }
                                ?>
                            </ul>
                            <p><?php echo $this->messageError; ?></p>
                        </section>
                <?php
                    }
                ?>
            </main>
        </body>
        </html>

    <?php
    }

}