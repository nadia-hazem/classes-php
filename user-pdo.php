
<?php
    session_start();

    class Userpdo
    {
        // attributs
        private $id;
        public $login;
        private $password;
        public $email;
        public $firstname;
        public $lastname;
        private $bdd;

        // Méthodes  
        public function __construct() { 
            $host = 'localhost';
            $dbname = 'classes';
            $dbuser = 'root';
            $dbpass = '';

            /* $this->bdd = new PDO('mysql:host=localhost; dbname=classes; charset=utf8', 'root', ''); */
            try {
                $this->bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
                $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } 
            catch (PDOException $e) 
            {
                echo "Erreur : " . $e->getMessage();
                die();
            }
            //vérification de session
            if(isset($_SESSION['user'])) 
            {
                $this->id = $_SESSION['user']['id'];
                $this->login = $_SESSION['user']['login'];
                $this->password = $_SESSION['user']['password'];
                $this->email = $_SESSION['user']['email'];
                $this->firstname = $_SESSION['user']['firstname'];
                $this->lastname = $_SESSION['user']['lastname'];  
            }
        } 

        public function register($login, $password, $email, $firstname, $lastname)
        {
                if ($login != "" && $password != "" && $email != "" && $firstname != "" && $lastname != "") {
                    $request = "SELECT * FROM utilisateurs WHERE login = :login ";
                    // préparation de la requête
                    $select = $this->bdd->prepare($request);
                    // exécution de la requête avec liaison des paramètres
                    $select->execute(array(
                        ':login' => $login
                    ));
                    $fetch = $select->fetchAll();
                    $row = count($fetch);
                    if ($row == 0) {
                        $register = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES
                        (:login, :password, :email, :firstname, :lastname)";
                        // préparation de la requête             
                        $insert = $this->bdd->prepare($register);
                        // exécution de la requête avec liaison des paramètres
                        $insert->execute(array(
                            ':login' => $login,
                            ':password' => $password,
                            ':email' => $email,
                            ':firstname' => $firstname,
                            ':lastname' => $lastname
                        ));
                        echo "Inscription réussie !";
                    }
                    else {
                        $error = "Ce login existe déjà !";
                        return $error;
                    }
                }
                else {
                    echo "Vous devez remplir tous les champs !";
                }
        }

        public function connect($login, $password)
        {
            if($login != "" && $password != "") {
                $request = "SELECT * FROM utilisateurs WHERE login = :login AND password = :password ";
                // préparation de la requête
                $select = $this->bdd->prepare($request);
                // exécution de la requête avec liaison des paramètres
                $select->execute(array(
                    ':login' => $login,
                    ':password' => $password
                ));
                $result = $select->fetchAll();
                
                if(count($result) == 1) {
                    $select->execute(array(
                        ':login' => $login,
                        ':password' => $password
                    ));
                    $result = $select->fetch(PDO::FETCH_ASSOC);
                    /* $this->id = $result['id'];
                    $this->login = $result['login'];
                    $this->password = $result['password'];
                    $this->email = $result['email'];
                    $this->firstname = $result['firstname'];
                    $this->lastname = $result['lastname'];  */ 
                    $_SESSION['user']= [
                        'id' => $result['id'],
                        'login' => $result['login'],
                        'password' => $result['password'],
                        'email' => $result['email'],
                        'firstname' => $result['firstname'],
                        'lastname' => $result['lastname']
                    ]; 
                    echo "Connexion réussie !";     
                }
                else {
                    echo "Login ou mot de passe incorrect";
                }
            }
            else {
                echo "Veuillez saisir un login et un mot de passe";
            }
        }

        public function disconnect()
        {
            if($this->isConnected()) 
                {
                // Close connection
                echo "déconnexion réussie";
                session_destroy();
                }
                else {
                    echo "Vous n'êtes pas connecté(e) !";
                }
        }

        public function delete()
        {   
            if($this->isConnected()) 
            {
                $delete = "DELETE FROM utilisateurs WHERE id = :id ";
                // préparation de la requête
                $delete = $this->bdd->prepare($delete);
                // exécution de la requête avec liaison des paramètres
                $delete->execute(array(
                    ':id' => $this->id
                ));

                $result = $delete->fetchAll();

                if ($result == TRUE) {
                    echo "Utilisateur supprimé !"; 
                    session_destroy();
                }
                else{
                    echo "Erreur lors de la suppression de l'utilisateur !";
                }
            }
            else {
                echo "Vous devez être connecté pour supprimer votre compte !";
            }
            $this->bdd = null; 
        }

        public function update($login, $password, $email, $firstname, $lastname)
        {
            if($this->isConnected())
            {
                if ($login != "" && $password != "" && $email != "" && $firstname != "" && $lastname != "") 
                {
                    $_SESSION['user']['login'] = $login;
                    $_SESSION['user']['password'] = $password;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['firstname'] = $firstname;
                    $_SESSION['user']['lastname'] = $lastname; 

                    $update = "UPDATE utilisateurs SET login = :login, password = :password, email = :email, firstname = :firstname, lastname = :lastname WHERE id = :id ";
                    // préparation de la requête
                    $select = $this->bdd->prepare($update);
                    // exécution de la requête avec liaison des paramètres
                    $select->execute(array(
                        ':login' => $login,
                        ':password' => $password,
                        ':email' => $email,
                        ':firstname' => $firstname,
                        ':lastname' => $lastname,
                        ':id' => $this->id
                    ));
                    echo "Mise à jour terminée !";
                }
                else {
                    echo "Vous devez remplir tous les champs !";
                }
            }
            else {
                echo "Vous devez être connecté pour modifier vos informations !";
            }
        }

        public function isConnected()
        {
            if($this->id != null && $this->login != null && $this->password != null && $this->email != null && $this->firstname != null && $this->lastname != null) {
                return true;
            }
            else {
                return false;
            }
        }

        public function getAllInfos()
        {
            if($this->isConnected()) 
            {
                echo "login : " . $this->login . "<br>";
                echo "password : " . $this->password . "<br>";
                echo "email : " . $this->email . "<br>";
                echo "firstname : " . $this->firstname . "<br>";
                echo "lastname : " . $this->lastname . "<br>";
            }
            else {
                echo "Vous devez être connecté(e) pour voir vos informations !";
            }

        }

        public function getLogin()
        {
            if($this->isConnected()) 
            {
                echo "login : " . $this->login . "<br>";
            }
            else {
                echo "Vous devez être connecté(e) pour voir vos informations !";
            }
        }

        public function getEmail()
        {
            if($this->isConnected()) 
            {
                echo "email : " . $this->email . "<br>";
            }
            else {
                echo "Vous devez être connecté(e) pour voir vos informations !";
            }
        }

        public function getFirstname()
        {
            if($this->isConnected()) 
            {
                echo "firstname : " . $this->firstname . "<br>";
            }
            else {
                echo "Vous devez être connecté(e) pour voir vos informations !";
            }
        }

        public function getLastname()
        {
            if($this->isConnected()) 
            {
                echo "lastname : " . $this->lastname . "<br>";
            }
            else {
                echo "Vous devez être connecté(e) pour voir vos informations !";
            }
        }
    }

$user = new User();
// test register PDO   //OK
// echo $user->register('test2', 'test2', 'test2@test2.fr', 'testname2', 'testprenom2');

//test connect  PDO //OK
// echo $user->connect('test', 'test');

//test disconnect PDO  //OK
// echo $user->disconnect();

//test update PDO //OK
// echo $user->update('test1', 'test1', 'test1@test.fr', 'testnom1', 'testprenom1');

//test isConnected PDO  //OK
//echo $user->isConnected();

//test getAllInfos PDO  //OK
// echo $user->getAllInfos();

//test getLogin PDO //OK
//echo $user->getLogin();

//test getEmail PDO //OK
//echo $user->getEmail();

//test getFirstname PDO //OK
//echo $user->getFirstname();

//test getLastname PDO //OK
//echo $user->getLastname();

/* echo "<br>";
echo $user->login;
echo "<br>";
echo $user->email;
echo "<br>";
echo $user->firstname;
echo "<br>";
echo $user->lastname; */


?>