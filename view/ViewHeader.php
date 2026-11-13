<?php
//Header commun à inclure dans les pages
//Navigation dynamique selon l'état connecté ou non

class ViewHeader {
    //ATTRIBUT
    private string $title;

    //CONSTRUCTEUR
    public function __construct(string $title = "The Shield"){
        $this->title = $title;
    }

    //GETTER ET SETTER
    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    //METHOD
    public function displayHeader(){
    ?>

        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $this->title ?></title>
        </head>
        <body>
            <header>
                <nav>
                    <a href="index.php">Accueil</a>

                    <?php
                        //Vérifier si un manager est connecté
                        if(isset($_SESSION["id"])){
                    ?>
                            <a href="moncompte.php">Mon Compte</a>
                            <a href="agents.php">Agents</a>
                            <a href="deco.php">Se Déconnecter</a>
                    <?php
                        };
                    ?>
                </nav>
            </header>

    <?php
    }
}