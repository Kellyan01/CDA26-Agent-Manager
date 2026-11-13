<?php

class ViewCompte{
    public function display(){
?>
    <main>
            <h2>Mon Compte</h2>
            <p><?php echo(htmlspecialchars($_SESSION["firstname"])." ".htmlspecialchars($_SESSION["lastname"])) ?></p>
            <p>Email : <?php echo(htmlspecialchars($_SESSION["email"])) ?></p>
            <p>Role : <?php echo(htmlspecialchars($_SESSION["role"])) ?></p>
            <p>Date d'enregistrement : <?php echo(htmlspecialchars($_SESSION["createdAt"])) ?></p>
        </main>
        
    </body>
    </html>
<?php
    }
}