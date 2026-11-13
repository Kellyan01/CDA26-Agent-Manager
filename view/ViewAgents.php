<?php
class ViewAgent{
    //ATTRIBUT
    private array $tiers;
    private array $archetypes;
    private array $listAgents;
    private string $message;

    //GETTER ET SETTER
    /**
     * Get the value of tiers
     *
     * @return array
     */
    public function getTiers(): array {
        return $this->tiers;
    }

    /**
     * Set the value of tiers
     *
     * @param array $tiers
     *
     * @return self
     */
    public function setTiers(array $tiers): self {
        $this->tiers = $tiers;
        return $this;
    }

    /**
     * Get the value of archetypes
     *
     * @return array
     */
    public function getArchetypes(): array {
        return $this->archetypes;
    }

    /**
     * Set the value of archetypes
     *
     * @param array $archetypes
     *
     * @return self
     */
    public function setArchetypes(array $archetypes): self {
        $this->archetypes = $archetypes;
        return $this;
    }

    /**
     * Get the value of listAgents
     *
     * @return array
     */
    public function getListAgents(): array {
        return $this->listAgents;
    }

    /**
     * Set the value of listAgents
     *
     * @param array $listAgents
     *
     * @return self
     */
    public function setListAgents(array $listAgents): self {
        $this->listAgents = $listAgents;
        return $this;
    }

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
    
    public function display(){
?>
        <main>
                <h1>Agents of Shield</h1>
                <section>
                    <h2>Enregistrement d'un agent</h2>
                    <form action="" method="post">
                        <label for="name">Nom : <input id="name" type="text" name="name"></label>
                        <label for="dob">Date de naissance : <input id="dob" type="text" name="dob"></label>
                        <label for="tier">Tier : <select name="tier" id="tier">
                            <?php
                                //Affichage des options pour le Tier
                                foreach($this->tiers as $tier){
                            ?>
                                    <option value="<?php echo(htmlspecialchars($tier['Id_Tier'])) ?>"><?php echo(htmlspecialchars($tier['tier'])) ?></option>
                            <?php
                                }
                            ?>
                            </select>
                        </label>
                        <label for="archetype">Archetype : <select name="archetype" id="archetype">
                            <?php
                                //Affichage des options pour le Tier
                                foreach($this->archetypes as $archetype){
                            ?>
                                    <option value="<?php echo(htmlspecialchars($archetype['Id_Archetype'])) ?>"><?php echo(htmlspecialchars($archetype['archetype'])) ?></option>
                            <?php
                                }
                            ?>
                            </select>
                        </label>
                        <input type="submit" name="submit">
                    </form>
                    <p><?php echo($this->message) ?></p>
                </section>
                <section>
                    <h2>Liste des agents</h2>
                    <ul>
                        <?php
                            foreach($this->listAgents as $agent){
                        ?>
                                <li><?php 
                                //Affichage des données avec nettoyage du code malveillant
                                echo(htmlspecialchars($agent['name'])." - ".htmlspecialchars($agent['archetype'])." - ".htmlspecialchars($agent['dob'])." - ".htmlspecialchars($agent['created_at'])." - ".htmlspecialchars($agent['tier'])) ?></li>
                        <?php
                            }
                        ?>
                    </ul>
                </section>
            </main>
        </body>
        </html>

<?php
    }


    
}