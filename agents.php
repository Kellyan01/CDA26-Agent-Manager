<?php
//Démarrer la session
session_start();

//Variable d'affichage
$title = "Agents of Shield";
$listAgents = [];
$tiers = [];
$archetypes = [];
$message = "";

//Accessible uniquement si connecté
if(!isset($_SESSION["id"])){
    //non connecté -> redirection vers accueil
    header('Location:index.php');
    exit;
}

//RECUPERATION DE LA LISTE DES TIERS ET DES ARCHETYPES
try{
    //Création du PDO
    $pdo = new PDO("mysql:host=localhost;dbname=agent_manager","root","root",[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    
    //Préparation de la requêt pour Tier
    $request = $pdo->prepare("SELECT t.Id_Tier, t.tier FROM tiers t");
    //Execution
    $request->execute();
    //Récupération
    $tiers = $request->fetchAll(PDO::FETCH_ASSOC);

    //Préparation de la requêt pour Archetype
    $request = $pdo->prepare("SELECT a.Id_Archetype, a.archetype FROM archetypes a");
    //Execution
    $request->execute();
    //Récupération
    $archetypes = $request->fetchAll(PDO::FETCH_ASSOC);

}catch(Exception $error){
    http_response_code(500);
    $message = $error->getMessage();
}

//ENREGISTREMENT D'UN AGENT
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
                
                try{
                    //Création du PDO
                    $pdo = new PDO("mysql:host=localhost;dbname=agent_manager","root","root",[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
                    //5. Vérifier si le nom est disponible
                    //5.1 Préparation de la requête
                    $request = $pdo->prepare("SELECT a.name FROM agents a WHERE a.name = ?");

                    //5.2 BindParam
                    $request->bindParam(1,$name,PDO::PARAM_STR);

                    //5.3 Exécution
                    $request->execute();

                    //5.4 Récupération
                    $data = $request->fetch(PDO::FETCH_ASSOC);

                    //5.5 Vérification si $data vide
                    if(empty($data)){
                        //6. Vérifier si l'archetype existe
                        //6.1 Préparation de la requête
                        $request = $pdo->prepare("SELECT a.archetype FROM archetypes a WHERE a.Id_Archetype = ?");

                        //6.2 BindParam
                        $request->bindParam(1,$archetype,PDO::PARAM_INT);

                        //6.3 Exécution
                        $request->execute();

                        //6.4 Récupération
                        $data = $request->fetch(PDO::FETCH_ASSOC);

                        //6.5 Vérification si $data vide
                        if(!empty($data)){
                            //7. Vérifier si le tier existe
                            //7.1 Préparation de la requête
                            $request = $pdo->prepare("SELECT t.tier FROM tiers t WHERE t.Id_Tier = ?");

                            //7.2 BindParam
                            $request->bindParam(1,$tier,PDO::PARAM_INT);

                            //7.3 Exécution
                            $request->execute();

                            //7.4 Récupération
                            $data = $request->fetch(PDO::FETCH_ASSOC);

                            //7.5 Vérification si $data vide
                            if(!empty($data)){
                                //8. Lancer l'enregistrement
                                //8.1 Préparation de la requête
                                $request = $pdo->prepare("INSERT INTO agents (`name`,dob,Id_Tier, Id_Archetype) VALUES (?,?,?,?)");

                                //8.2 BindParam
                                $request->bindParam(1,$name,PDO::PARAM_STR);
                                $request->bindParam(2,$dob,PDO::PARAM_STR);
                                $request->bindParam(3,$tier,PDO::PARAM_INT);
                                $request->bindParam(4,$archetype,PDO::PARAM_INT);

                                //8.3 Execution
                                $request->execute();

                                //8.4 Message
                                $message = htmlspecialchars($name)." a été enregistré.";

                            }else{
                                $message = "Ce Tier n'existe pas";
                            }
                            //8. Lancer l'enregistrement

                        }else{
                            $message = "Cet Archetype n'existe pas";
                        }
                        
                    }else{
                        $message = "Ce nom n'est pas disponible.";
                    }

                    
                }catch(Exception $error){
                    http_response_code(500);
                    $message = $error->getMessage();
                }
                
            }else{
                $message = "Archetype est invalide";
            }
        }else{
            $message = "Le Tier est invalide";
        }
        
    }else{
        $message = "Remplissez tous les champs.";
    }
}

//LISTES DES AGENTS
try{
    //Création du PDO
    $pdo = new PDO("mysql:host=localhost;dbname=agent_manager","root","root",[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

    //Préparation de la requête
    $request = $pdo->prepare("SELECT a.Id_Agent, a.name, a.dob, a.created_at, t.tier, ac.archetype, te.team_name FROM agents a INNER JOIN tiers t ON t.Id_Tier = a.Id_Tier INNER JOIN archetypes ac ON ac.Id_Archetype = a.Id_Archetype LEFT JOIN Teams te ON te.Id_Team = a.Id_Team");

    //Execution
    $request->execute();

    //Récupère
    $listAgents = $request->fetchAll(PDO::FETCH_ASSOC);
}catch(Exception $error){
   http_response_code(500);
   $message = $error->getMessage();
}


//Inscription d'un nouvel agent (attention : ne pas oublie le tier et l'archetype)
//Affichage de la liste des agents

include './header.php';
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
                        foreach($tiers as $tier){
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
                        foreach($archetypes as $archetype){
                    ?>
                            <option value="<?php echo(htmlspecialchars($archetype['Id_Archetype'])) ?>"><?php echo(htmlspecialchars($archetype['archetype'])) ?></option>
                    <?php
                        }
                    ?>
                    </select>
                </label>
                <input type="submit" name="submit">
            </form>
            <p><?php echo($message) ?></p>
        </section>
        <section>
            <h2>Liste des agents</h2>
            <ul>
                <?php
                    foreach($listAgents as $agent){
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