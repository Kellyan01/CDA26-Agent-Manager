<?php
//ACCUEIL
//Création d'un compte Manager
//Connexion d'un compte Manager
//Listing des Managers
//Affichage dynamique selon l'état de connexion

//Démarrage de la session
session_start();

//Déclaration des variables d'affichage
$title = "Accueil";
$message = "";
$messageConnection = "";
$listManagers = [];

//INSCRIPTION MANAGER
//1. Vérifier la réception du formulaire d'inscription
if(isset($_POST['submit'])){

    //2. Normaliser les données avec trim : firstname, lastname, email
    // On enlève les espaces avantr de vérifier les champs vide. Ainsi, si un champ obligatoire est constitué uniquement d'espace, alors il ne passera pas la vérification des champs vide. Par contre, on ne trim jamais le mot de passe, car espace peut en faire légitimement partie
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST["lastname"]);
    $email = trim($_POST["email"]);

    //3. Vérifier les champs vide
    if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($_POST["password"])){

        //4. Vérifier le format des données
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            if(mb_strlen($_POST["password"]) >= 12){
                //5. Hasher le mot de passe
                $password = password_hash($_POST["password"],PASSWORD_DEFAULT);

                //6. Vérifier si l'email est disponible
                try{
                    //Créer l'objet de connexion PDO
                    //Le mode d'erreur par Exception est le comportement par défaut, on n'est plus obligé de le mettre lors de la création de l'objet
                    $pdo = new PDO("mysql:host=localhost;dbname=agent_manager","root","root",[
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);

                    //A. Préparer la requête de lecture des données lié à l'email
                    $request = $pdo->prepare('SELECT u.email FROM users u WHERE u.email = ?');

                    //B. Relier le ? à mon email
                    $request->bindParam(1,$email,PDO::PARAM_STR);

                    //C. Exécuter la requête
                    $request->execute();

                    //D. Récupérer les données
                    $data = $request->fetch(PDO::FETCH_ASSOC);

                    //E. Vérifier si $data est vide. S'il est vide, l'emai lest disponible
                    if(empty($data)){
                        //7. Enregistrer le manager
                        //A. Préparer la requête de lecture des données lié à l'email
                        $request = $pdo->prepare('INSERT INTO users (firstname, lastname, email, `password`, Id_Role) VALUES(?,?,?,?,2)');

                        //B. Relier les ? à mes données
                        $request->bindParam(1,$firstname,PDO::PARAM_STR);
                        $request->bindParam(2,$lastname,PDO::PARAM_STR);
                        $request->bindParam(3,$email,PDO::PARAM_STR);
                        $request->bindParam(4,$password,PDO::PARAM_STR);

                        //C. Exécuter la requête
                        $request->execute();

                        //D. Affichage en nettoyant le code
                        $message = htmlspecialchars($firstname)." ".htmlspecialchars($lastname)." est correctement enregistré.";
                    }else{
                        $message = "Email indisponible.";
                    }

                }catch(Exception $error){
                    http_response_code(500);
                    $message = $error->getMessage();

                    //Erreur 500 : Serveur indisponible
                }

            }else{
                $message = "le mot de passe doit faire au moins 12 caractères.";
            }
        }else{
            $message = "l'email n'est pas au bon format.";
        }

        
    }else{
        $message = "Veuillez remplir tous les champs.";
    }
}

//CONNEXION MANAGER
//1. Vérifier la réception du formulaire d'inscription
if(isset($_POST["submitConnection"])){
    //2. Normaliser les données avec trim : email
    $email = trim($_POST["emailConnection"]);

    //3. Vérifier les champs vide
    if(!empty($email) && !empty($_POST["passwordConnection"])){
        //4. Vérifier le format des données
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            
            if(mb_strlen($_POST["passwordConnection"]) >= 12){
                
                try{
                    //5. Vérifier si le compte existe 
                    //Créer l'objet de connexion PDO
                    $pdo = new PDO("mysql:host=localhost;dbname=agent_manager","root","root",[
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);

                    //A.Préparer la requête
                    $request = $pdo->prepare("SELECT u.Id_User, u.firstname, u.lastname, u.email, u.password, u.created_at, r.role FROM users u INNER JOIN roles r ON u.Id_Role = r.Id_Role WHERE u.email = ?");

                    //B.BindParam
                    $request->bindParam(1,$email, PDO::PARAM_STR);

                    //C.Exécuter la requête
                    $request->execute();

                    //D.Récupérer les données
                    $data = $request->fetch(PDO::FETCH_ASSOC);

                    //E. Vérifier si $data est rempli -> le compte existe
                    if(!empty($data)){
                        //6. Vérifier les mots de passe
                        if(password_verify($_POST["passwordConnection"], $data["password"])){
                            //7. Connecter le manager
                            $_SESSION["id"] = $data["Id_User"];
                            $_SESSION["firstname"] = $data["firstname"];
                            $_SESSION["lastname"] = $data["lastname"];
                            $_SESSION["email"] = $data["email"];
                            $_SESSION["createdAt"] = $data["created_at"];
                            $_SESSION["role"] = $data["role"];

                            $messageConnection = htmlspecialchars($_SESSION["firstname"])." est connecté";

                        }else{
                        $messageConnection = "L'email et/ou le mot de passe est invalide.";
                    }
                        
                    }else{
                        $messageConnection = "L'email et/ou le mot de passe est invalide.";
                    }

                    
                }catch(Exception $error){
                    http_response_code(500);
                    $message = $error->getMessage();

                    //Erreur 500 : Serveur indisponible
                }
                
            }else{
                $messageConnection = "Mot de passe doit faire au moins 12 caractères";
            }
            
        }else{
            $messageConnection = "Format d'email invalide";
        }
        

    }else{
        $messageConnection = "Tous les champs sont obligatoires.";
    }
    
}

//LISTE DES MANAGERS
//Vérifier si un manager est connecté
if(isset($_SESSION["id"])){
    try{
        //Créer l'objet de connexion PDO
        $pdo = new PDO("mysql:host=localhost;dbname=agent_manager","root","root",[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

        //A.Préparer la requête
        $request = $pdo->prepare("SELECT u.Id_User, u.firstname, u.lastname, u.email, u.password, u.created_at, r.role FROM users u INNER JOIN roles r ON u.Id_Role = r.Id_Role WHERE r.role = 'Manager'");

        //B.Exécuter la requête
        $request->execute();

        //C.Récupérer les données
        $listManagers = $request->fetchAll(PDO::FETCH_ASSOC);
                        
    }catch(Exception $error){
        http_response_code(500);
        $message = $error->getMessage();

        //Erreur 500 : Serveur indisponible
    }
}

//include du Header
include './header.php';
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
            <p><?php echo $message ?></p>
        </section>
        <section>
            <h2>Connexion</h2>
            <form action="" method="post">
                <label for="emailConnection">Email :<input id="emailConnection" type="text" name="emailConnection"></label>
                <label for="passwordConnection">Motde Passe :<input id="passwordConnection" type="text" name="passwordConnection"></label>
                <input type="submit" name="submitConnection">
            </form>
            <p><?php echo $messageConnection ?></p>
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
                            foreach($listManagers as $manager){
                        ?>
                                <li><?php 
                                //Affichage des données avec nettoyage du code malveillant
                                echo(htmlspecialchars($manager['firstname'])." ".htmlspecialchars($manager['lastname'])." - ".htmlspecialchars($manager['email'])) ?></li>
                        <?php
                            }
                        ?>
                    </ul>
                </section>
        <?php
            }
        ?>
    </main>
</body>
</html>