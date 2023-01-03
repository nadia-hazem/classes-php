
<?php
    session_start();

    class User
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
            $this->bdd = mysqli_connect('localhost', 'root', '', 'classes');
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
                    $request = "SELECT * FROM utilisateurs WHERE login = '$login' ";
                    $exec = mysqli_query($this->bdd, $request);                    
                    $row = mysqli_num_rows($exec);
                    /* var_dump($row); */
                    if ($row == 0) {
                        $request2 = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES
                        ('$login', '$password', '$email', '$firstname', '$lastname')";
                        $requestexec = mysqli_query($this->bdd, $request2);
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
                $request = "SELECT * FROM utilisateurs WHERE login = '$login' AND password = '$password' ";
                $exec = mysqli_query($this->bdd, $request);
                $result = mysqli_fetch_assoc($exec);
                $row = mysqli_num_rows($exec);
                
                if($row == 1) {
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
                $delete = "DELETE FROM utilisateurs WHERE id = '$this->id' ";

                $result = $this->bdd->query($delete);
                if ($result == TRUE) {
                    echo "Utilisateur supprimé !"; 
                    session_destroy();
                }
                else{
                    echo "Error:" . $delete . "<br>" . $this->bdd->error;
                }
            }
            else {
                echo "Vous devez être connecté pour supprimer votre compte !";
            }
            mysqli_close($this->bdd); 
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

                    $update = "UPDATE utilisateurs SET login = '$login', password = '$password', email = '$email', firstname = '$firstname', lastname = '$lastname' WHERE id = '$this->id' ";
                    $exec = mysqli_query($this->bdd, $update);
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
// test register    //OK
// echo $user->register('test', 'test', 'test@email.com', 'testname', 'testprenom');

//test connect  //OK
//echo $user->connect('test', 'test');

//test disconnect   //OK
//echo $user->disconnect();

//test update //OK
//echo $user->update('test', 'test', 'test@test.fr', 'testnom', 'testprenom');

//test isConnected   //OK
// echo $user->isConnected();

//test getAllInfos   //OK
//echo $user->getAllInfos();

//test getLogin  //OK
//echo $user->getLogin();

//test getEmail  //OK
//echo $user->getEmail();

//test getFirstname  //OK
//echo $user->getFirstname();

//test getLastname  //OK
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