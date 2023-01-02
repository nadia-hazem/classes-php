
<?php
    session_start();

    class User
    {
        // attributs
        private $id;
        public $login;
        public $email;
        public $firstname;
        public $lastname;
        private $password;
        private $bdd;
    
        // Méthodes  
        public function __construct() { 
            $this->bdd = mysqli_connect('localhost', 'root', '', 'classes');
            //vérification de session
            if(isset($_SESSION['user'])) {
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
                    var_dump($row);
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
/*                     $this->id = $result['id'];
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
            session_destroy();
            echo "déconnexion réussie";
        }

        public function delete()
        {
            if()
            $request = "DELETE * FROM utilisateurs WHERE 'id' = $this->id ";
            $exec = mysqli_query($this->bdd, $request);
            $result = mysqli_fetch_assoc($exec);
            $row = mysqli_num_rows($exec);
        }

        public function update()
        {
           ;
        }

        public function isConnected()
        {
           ;
        }

        public function getAllInfos()
        {
           ;
        }

        public function getLogin()
        {
           ;
        }

        public function getEmail()
        {
           ;
        }

        public function getFirstname()
        {
           ;
        }

        public function getLastname()
        {
           ;
        }
    }
  
$user = new User();
// test register
// echo $user->register('test', 'test', 'test@email.com', 'testname', 'testprenom');

//test connect
// echo $user->connect('test', 'test');

//test disconnect
echo $user->disconnect();
echo "<br>";
echo $user->login;
echo "<br>";
echo $user->email;
echo "<br>";
echo $user->firstname;
echo "<br>";
echo $user->lastname;


?>