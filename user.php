
<?php
    
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
            $this->bdd = new mysqli('localhost', 'root', '', 'classes');
        } 

        public function register($login, $password, $email, $firstname, $lastname)
        {
            $conn = $this->bdd;
                if ($login != "" && $password != "" && $email != "" && $firstname != "" && $lastname != "") {
                    $request = "SELECT count (*) FROM utilisateurs WHERE login = $login";
                    $exec = $conn->query($request);
                    $result = mysqli_fetch_array($exec);
                    $count = $result['count (*)'];
                    if ($count == 0) {
                        $request = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES
                        ('$login', '$password', '$email', '$firstname', '$lastname')";
                        $exec = $conn->query($request);
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

        public function connect()
        {
           ;
        }

        public function disconnect()
        {
           ;
        }

        public function delete()
        {
           ;
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
  
$student = new User();

?>