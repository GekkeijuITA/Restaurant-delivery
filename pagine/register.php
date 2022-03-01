<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    function sendMail($toAddress,$subject,$body)
    {
        $mail = new PHPMailer(true);
        try {       
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->CharSet = 'UTF-8';          
            $mail->Encoding = 'base64';    
            $mail->isSMTP();                                          
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'daPin.ristorante@gmail.com';                     
            $mail->Password   = 'daPinCalvino';                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
            $mail->Port       = 465;                                    
        
            $mail->setFrom('daPin.ristorante@gmail.com','daPin ristorante');
            $mail->addAddress($toAddress);

            $mail->isHTML(true);                                 
            $mail->Subject = $subject;
            $mail->Body = $body;
        
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }        
    }
	$ACCOUNT = FALSE;
	$ERRORE = FALSE; 
    $LONTANO = FALSE;
    $TELEFONO = FALSE;
    $CAPSBAGLIATO = FALSE; // MODAL DA FARE
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "daPin";
    $conn = mysqli_connect($servername , $username , $password , $dbName);
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
	if(isset($_POST['register']))
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
					$ACCOUNT = TRUE;
					break;
				}
			}
		}
		if(!$ACCOUNT)
		{
            $citta = $_POST['citta'];
            if(strtolower($citta) == "genova")
            {
                $cap = $_POST['cap'];
                if($cap >= 16121 && $cap <= 16167 || $cap == 16338)
                {
                    $telefono = $_POST['telefono'];
                    $sql = "SELECT nTelefono FROM Cliente WHERE nTelefono = $telefono";
                    $result = $conn -> query($sql);
                    if($result -> num_rows > 0)
                    {
                        $TELEFONO = TRUE;
                    }
                    else
                    {
                        $nome = $_POST['nome'];
                        $cognome = $_POST['cognome'];
                        $via = $_POST['via'];
                        $civico = $_POST['civico'];
                        $interno = $_POST['interno'];
                        $password = $_POST['password'];
                        $hash = hash('sha256',$password);
                        $nCarta = $_POST['nCarta'];
                        $dataScadenza = $_POST['scadenza'];
                        $circuito = $_POST['circuito'];
                        $sql = "INSERT INTO CartaCredito VALUES('$nCarta' , '$dataScadenza', '$circuito' , '$email')";
                        $conn -> query($sql);
                        $sql = "INSERT INTO Indirizzo(via,civico,cap,interno,utente) VALUES ('$via' , '$civico' , '$cap' , '$interno' , '$email')";
                        $conn -> query($sql);
                        $sql = "INSERT INTO Cliente VALUES ('$email' , '$nome' , '$cognome' , '$telefono' , '$hash' , 'img/account/accountLogo.png')";
                        if($conn -> query($sql) === TRUE)
                        {
                            $_SESSION['email'] = $email;
                            $_SESSION['password'] = $password;
                            $body = "
                            <p>Benvenuto $nome nel nostro ristorante, ora potrai usufruire di tutti i nostri servizi delivery!</p></br>
                            <p>daPin</p>
                            ";
                            sendMail($email,'Registrazione completata!',$body);
                            header("location: login.php");
                        }
                        else
                        {
                            $ERRORE = TRUE;
                            //Modal errore
                        }
                    }                     
                } 
                else
                {
                    $CAPSBAGLIATO = TRUE;
                }          
            }
            else
            {
                $LONTANO = TRUE;
            }
		}
	}
?>
<!DOCTYPE html>
<html>
    <head>
    <title>da Pin - Registrazione</title>
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
                            <li><a class="dropdown-item" style="color:#59A0F8" href="menu.php?tipo=menu">Visualizza Menu</a></li>
                            <?php
                                if(!empty($_SESSION["utente"]) || !empty($_SESSION["admin"]))
                                {
                                    echo '
                                        <li><a class="dropdown-item" style="color:#59A0F8" href="carrello.php">Ordina</a></li><!--Da togliere nel caso-->
                                        <li><a class="dropdown-item" style="color:#59A0F8" href="catering.php">Catering</a></li>                                     
                                    ';
                                }
                            ?>
                        </ul>
                    </li>
                </ul>
                <ul class="nav justify-content-end">
                    <li class="nav-item dropstart">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:white;">
                            <img src="../img/account/accountLogo.png" alt="accountLogo"/>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                                if(!empty($_SESSION["utente"]))
                                {
                                    echo '
                                        <li>
                                            <a class="dropdown-item" style="color:#59A0F8" href="account.php"><i class="fa fa-user-circle"></i> Account</a>
                                        </li>                                     
                                        <div class="dropdown-divider"></div>
                                        <li>
                                            <form method="post" action="">
                                                <input type="submit" style="color:#59A0F8" class="btn w-100" name="submit" value="Esci">
                                            </form>
                                        </li>                                        
                                    ';
                                }
                                    else if(!empty($_SESSION["admin"]))
                                    {
                                        echo '
                                            <li>
                                                <a class="dropdown-item" style="color:#59A0F8" href="dashboardAdmin.php"><i class="fa fa-user-circle"></i> Dashboard</a>
                                            </li>                                     
                                            <div class="dropdown-divider"></div>
                                            <li>
                                            <form method="post" action="">
                                                <input type="submit" style="color:#59A0F8" class="btn w-100" name="submit" value="Esci">
                                            </form>
                                            </li>                                        
                                        ';
                                    }
                                    else{
                                        echo' 
                                            <li>
                                                <a class="dropdown-item" style="color:#59A0F8" href="admin.php"><i class="fa fa-user-circle"></i> Area Staff</a>
                                            </li>                                        
                                            <li>
                                                <a class="dropdown-item" style="color:#59A0F8" href="login.php"><i class="fa fa-user-circle"></i> Login/Registrazione</a>
                                            </li>
                                        ';
                                    }
                                ?>
                                </ul>
                        </li>                    
                    </ul>   
                </div>
            </div>
        </nav>
        <section style="background-color: #B5E5E9;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="card" style="border-radius: 1rem; max-width:900px;">
                        <div class="card-body p-4 p-lg-5 text-black formRegister center">
                            <form method="post" action="">
                                <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Registra il tuo account</h3>
                                <div class="row">
                                    <div class="form-group col-md-4 pb-2">
                                      <input type="text" class="form-control" name="nome" placeholder="Nome">
                                    </div>
                                    <div class="form-group col-md-4 pb-2">
                                      <input type="text" class="form-control" name="cognome" placeholder="Cognome">
                                    </div>
                                    <div class="form-group col-md-4 pb-2">
                                        <input type="tel" class="form-control" name="telefono" placeholder="Numero di telefono">
                                      </div>
                                </div>
                                <div class="row">
                                  <div class="form-group col-md-6 pb-2">
                                    <input type="email" class="form-control" name="email" placeholder="Indirizzo e-mail">
                                  </div>
                                  <div class="form-group col-md-6 pb-2">
                                    <input type="password" class="form-control" name="password" pattern=".{8,}" title="Inserire otto o più caratteri" placeholder="Password">
                                  </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4 pb-2">
                                      <input type="text" class="form-control" name="via" placeholder="Via">
                                    </div>
                                    <div class="form-group col-md-4 pb-2">
                                        <input type="text" class="form-control" pattern="[0-9]+" name="civico" placeholder="Civico">
                                    </div>
                                    <div class="form-group col-md-4 pb-2">
                                        <input type="text" class="form-control" pattern="[0-9]+" name="interno" placeholder="Interno">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 pb-2">
                                        <input type="text" class="form-control" pattern="[0-9]+" name="cap" placeholder="CAP" minlength="5" maxlength="5">
                                    </div>
                                    <div class="form-group col-md-6 pb-2">
                                        <input type="text" class="form-control" name="citta" placeholder="Città" required>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4 pb-2">
                                        <input type="text" class="form-control" pattern="[0-9]+" name="nCarta" placeholder="Numero cartaCredito" minlenght="13" maxlenght="16" required>
                                      </div>
                                    <div class="form-group col-md-4 pb-2">
                                        <input type="month" class="form-control" name="scadenza" placeholder="Data di scadenza della carta" required>
                                    </div>
                                    <div class="form-group col-md-4 pb-">
                                        <select class="form-control opzioni" name="circuito" required>
                                            <option>Visa</option>
                                            <option>Mastercard</option>
                                            <option>Paypal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group pb-2">
                                    <input type="submit" class="btn btn-dark btn-lg btn-block" name="register" value="Registrati">
                                </div>
                              </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!--Modal account esistente-->
    <div class="modal fade" id="existingAccount" tabindex="-1" aria-labelledby="existingAccountLabel" aria-hidden="true" style="color:black;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existingAccountLabel">Attenzione!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    L'account esiste già!
                </div>
                <div class="modal-footer">                               
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal errore-->
    <div class="modal fade" id="error" tabindex="-1" aria-labelledby="errorLabel" aria-hidden="true" style="color:black;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorLabel">Attenzione!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Errore! <?php echo "Error: " . $sql . "<br>" . $conn -> error;?>
                </div>
                <div class="modal-footer">                               
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal lontano-->
    <div class="modal fade" id="distant" tabindex="-1" aria-labelledby="distantLabel" aria-hidden="true" style="color:black;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="distantLabel">Attenzione!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sei troppo lontano per poterti registrare!
                </div>
                <div class="modal-footer">                               
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>  
    <!--Modal telefono-->
    <div class="modal fade" id="phone" tabindex="-1" aria-labelledby="phoneLabel" aria-hidden="true" style="color:black;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneLabel">Attenzione!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Questo numero è già stato utilizzato!
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
    if($ACCOUNT)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#existingAccount").modal("show");
                });
            </script>';             
    }
    if($ERRORE)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#error").modal("show");
                });
            </script>';             
    }
    if($LONTANO)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#distant").modal("show");
                });
            </script>';             
    }
    if($TELEFONO)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#phone").modal("show");
                });
            </script>';             
    }           	 
?>
