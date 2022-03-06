<?php
	$ACCOUNT = TRUE;
    $PASSWORD = TRUE; 
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "daPin";
    $conn = mysqli_connect($servername , $username , $password , $dbName);    
    if(!empty($_SESSION['email']))
    {
        $emailSession = $_SESSION['email'];
        $passwordSession = $_SESSION['password'];
    }
    if(!empty($_SESSION["utente"]))
    {
        $email = $_SESSION["utente"];
        $sql = "SELECT immagine FROM Cliente WHERE email = '$email'";
        $immagineProfilo = (($conn -> query($sql)) -> fetch_assoc())["immagine"];
    }
    else
    {
        $immagineProfilo = "img/account/accountLogo.png";
    }   
    if(!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }	
    if(isset($_POST['destroy']))
    {
        while(!session_unset())
        {
            session_unset();
        }
        session_destroy();
        header("location: index.php");
    }
	if(isset($_POST['login']))
	{
		$email = $_POST['email'];

		//Controllo che l'utente non esista
		$sql = "SELECT * FROM Cliente";
		$result = $conn -> query($sql);
		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_assoc())
			{
				if($row['email'] == $email)
				{
                    $password = hash("sha256" , $_POST['password']);
					if($row['psw'] == $password)
                    {
                        $_SESSION['utente'] = $email;
                        header("location: ../index.php");
                    }
                    else
                    {
                        $PASSWORD = FALSE;
                    }
				}
                else
                {
                    $ACCOUNT = FALSE;
                }
			}
		}
        else
        {
            $ACCOUNT = FALSE;
        }
	}
?>
<!DOCTYPE html>
<html>
    <head>
    <title>da Pin - Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="../fontawesome/css/all.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
        <link rel="icon" href="../img/pinIcon.png">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">
                    <img src="../img/pin4.png" alt="logo" width="90" height="84">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.php"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="info.php"><i class="fa fa-info"></i> Info</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cutlery"></i>     Menu</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" style="color:#59A0F8" href="menu.php?tipo=tutti">Visualizza Menu</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav justify-content-end">
                        <li class="nav-item dropstart">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:white;">
                                <img src="../img/account/accountLogo.png" alt="accountLogo"/>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" style="color:#59A0F8" href="admin.php"><i class="fa fa-user-circle"></i> Area Staff</a>
                                </li>                                        
                                <li>
                                    <a class="dropdown-item" style="color:#59A0F8" href="register.php"><i class="fa fa-user-circle"></i> Login/Registrazione</a>
                                </li>
                            </ul>
                        </li>                    
                    </ul>   
                </div>
            </div>
        </nav> 
        <section  style="background-color: #B5E5E9;">
            <div class="container py-5  h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                        <div class="col-md-6 col-lg-5 d-none d-md-block">
                        <img
                            src="../img/genova8.jpg"
                            alt="login form"
                            class="img-fluid h-100" style="border-radius: 1rem 1rem 1rem 1rem;"
                        />
                        </div>
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                        <div class="card-body p-4 p-lg-5 text-black formLogin">

                            <form method="post" action="">
                                
                                <div class="d-flex align-items-center mb-3 pb-1">
                                <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                </div>

                                <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Accedi al tuo account</h3>

                                <div class="form-outline mb-4">
                                    <input type="email" id="form2Example17" name="email" placeholder="Indirizzo e-mail" class="form-control form-control-lg" value="<?php if(!empty($_SESSION['email'])){echo $_SESSION['email'];}?>" required/>
                                </div>

                                <div class="form-outline mb-4">
                                    <input type="password" id="password" name="password" placeholder="Password" class="form-control form-control-lg" value="<?php if(!empty($_SESSION['password'])){echo $_SESSION['password'];}?>" pattern=".{8,}" required/>
                                </div>

                                <div class="pt-1 mb-4">
                                    <input type="submit" class="btn btn-dark btn-lg btn-block" name = "login" value="Login">
                                </div>

                                <p class="mb-5 pb-lg-2" style="color: #2DBDD5;">Non hai un account? <a href="register.php" style="color: #6CC8F3;">Registrati</a></p>
                            </form>

                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </section>
        <!--Modal account inesistente-->
        <div class="modal fade" id="existingAccount" tabindex="-1" aria-labelledby="existingAccountLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existingAccountLabel">Attenzione!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        L'account non esiste! Registrati.
                    </div>
                    <div class="modal-footer">                               
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal password sbagliata-->
        <div class="modal fade" id="wrongPassword" tabindex="-1" aria-labelledby="wrongPasswordLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="wrongPasswordLabel">Attenzione!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Password sbagliata!
                    </div>
                    <div class="modal-footer">                               
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>        	
    </body>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>Ristorante</h4>
                    <div style="color:#ffffff">da Pin | Via Borzoli, 21 - 16153 Sestri Ponente (Genova) ITALIA | C.F. CLVPNI47R10D969Q | <br>P.Iva 123456789</div><br>
                    <a class="trans-color-text link" style="text-decoration: none" href="mailto:daPin.ristorante@gmail.com">daPin.ristorante@gmail.com</a>  <br><span itemprop="telefono:"><a class="trans-color-text link" style="text-decoration: none" href="tel:+390106508778">+39 010 650 8778</a></span><br>
                </div>
                <div class="footer-col">
                    <h4>Pagamenti</h4>
                    <div class="icon-container">
                        <i class="fa fa-cc-visa size" style="color:navy;">&nbsp;&nbsp;</i>
                        <i class="fa fa-cc-mastercard size" style="color:orange;">&nbsp;&nbsp;</i>
                        <i class="fa fa-cc-paypal size" aria-hidden="true" style="color:lightblue;">&nbsp;&nbsp;</i>
                    </div>
                    
                </div>
                <div class="footer-col">
                    <h4>Pagine</h4>
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../pagine/info.php">Info</a></li>
                        <li><a href="../pagine/menu.php?tipo=menu">Menu</a></li>
                        <li><a href="../pagine/catering.php">Catering</a></li>
                        <li><a href="../pagine/login.php">Login-Registrazione</a></li>
                </ul>
                </div>
                <div class="footer-col">
                    <h4>Seguici su</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com"><i class="fa fa-facebook"></i></a>
                        <a href="https://www.instagram.com"><i class="fa fa-instagram"></i></a>
                        <a href="https://wa.me/390106508778"><i class="fa fa-whatsapp"></i></a>
                    </div>
                    <h4>Sviluppato da</h4>
                    <div class="credits">
                        <a target="_blank" href="index.html"><img width="100" src="../img/calvino3.png" alt="logo" style="border-radius: 20%;width:160px;height:70px;"></a> 
                    </div>
                </div>
            </div>
        </div>
    </footer>       
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>      
</html>
<?php
    if(!$ACCOUNT)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#existingAccount").modal("show");
                });
            </script>';             
    }
    if(!$PASSWORD)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#wrongPassword").modal("show");
                });
            </script>';             
    }     	 
?>