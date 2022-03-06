<?php
    $ORDINATO = FALSE;
    $RIDER = TRUE;
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
        
            $mail->setFrom('daPin.ristorante@gmail.com');
            $mail->addAddress($toAddress);

            $mail->isHTML(true);                                 
            $mail->Subject = $subject;
            $mail->Body = $body;
        
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }        
    }
    function calculateDays($day,$dayMax,$month,$year)
    {
        if($day==$dayMax){
            $day=5;
            if($month == 12)
            {
                $month = 1;
                $year += 1;
            }
            else
            {
                $month += 1;
            }
        }
        else if($day + 5 > $dayMax)
        {
            $lastDays = ($dayMax - $day);
            $day = 5 - $lastDays;
            if($month == 12)
            {
                $month = 1;
                $year += 1;
            }
            else
            {
                $month += 1;
            }
        }
        else
        {
            $day += 5;
        }
        
        return [$day,$month,$year];
    }

    $today = getdate();
    $month = $today["mon"];
    $day = $today["mday"];
    $year = $today["year"];
    switch ($month)
    {
        case 1:
            [$day,$month,$year] = calculateDays($day,31,$month,$year);
            break;
        case 2:
            [$day,$month,$year] = calculateDays($day,28,$month,$year);
            break;
        case 3:
            [$day,$month,$year] = calculateDays($day,31,$month,$year);
            break;
        case 4:
            [$day,$month,$year] = calculateDays($day,30,$month,$year);
            break;
        case 5:
            [$day,$month,$year] = calculateDays($day,31,$month,$year);
            break;
        case 6:
            [$day,$month,$year] = calculateDays($day,30,$month,$year);
            break;
        case 7:
            [$day,$month,$year] = calculateDays($day,31,$month,$year);
            break;
        case 8:
            [$day,$month,$year] = calculateDays($day,31,$month,$year);
            break;
        case 9:
            [$day,$month,$year] = calculateDays($day,30,$month,$year);
            break;
        case 10:
            [$day,$month,$year] = calculateDays($day,31,$month,$year);
            break;
        case 11:
            [$day,$month,$year] = calculateDays($day,30,$month,$year);
            break;
        case 12:
            [$day,$month,$year] = calculateDays($day,31,$month,$year);
            break;            
    }
    if($month < 10)
    {
        $month = "0".$month;
    }
    if($day < 10)
    {
        $day = "0".$day;
    }
    $limit = $year."-".$month."-".$day."T13:00";
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
    $utente = $_SESSION['utente'];
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
    if(isset($_POST['submit']))
    {
        while(!session_unset())
        {
            session_unset();
        }
        session_destroy();
        header("location: ../index.php");
    }
    if(isset($_POST['paga']))
    {
        $STESSOGIORNO = 0;
        $tipoCatering = $_GET["catering"];
        $numPersone = $_POST["nPersone"];
        $pagamento = $_POST["pagamento"];
        $input = $_POST["data"];
        $dataOra = explode("T" , $input);
        $data = explode("-" , $dataOra[0]);
        $sql = "SELECT * FROM Ordine";
        $result = $conn -> query($sql);        
        while($row = $result -> fetch_assoc())
        {
            if($row["catering"] != NULL)
            {
                $id = $row["ID"];
                $prenotazioneDB = $row["prenotazione"];
                $dataOraDB = explode(" ",$prenotazioneDB);
                $dataDB = explode("-" , $dataOraDB[0]);
                if($data[1] == $dataDB[1] && $data[2] == $dataDB[2])
                {
                    $STESSOGIORNO += 1;
                }
            }
        }
        if($STESSOGIORNO < 2)
        {
            $sql = "SELECT codice FROM Ordine";
            $result = $conn -> query($sql);
            if($result -> num_rows > 0)
            {
                $sql = "SELECT MAX(codice) as codiceMax FROM Ordine";
                $codice = ($conn -> query($sql)) -> fetch_assoc();
                $codice = $codice["codiceMax"]+1;
            }
            else
            {
                $codice = 1;
            }   
            $sql = "INSERT INTO Ordine(cliente,nome,quantita,codice,catering,prenotazione) VALUES ('$email','$tipoCatering',1,$codice,1,'$input')";
            $conn -> query($sql);

            $titolo = "Il tuo ordine è stato confermato";
            $sql = "SELECT nome FROM Cliente WHERE email = '$utente'";
            $nome = (($conn -> query($sql))-> fetch_assoc())["nome"];
            $messaggio = "
                <p>Gentile ".$nome." abbiamo prenotato con successo il tuo catering</p></br>
            ";
            $messaggio .= "
                <p>Il tuo ordine:</p></br>
                <table>
            ";
            $sql = "SELECT * FROM Catering WHERE tipo = '$tipoCatering'";
            $prezzo = ((($conn -> query($sql)) -> fetch_assoc())["prezzo"]);
            $messaggio .= "
                <tr>
                    <td>Catering di $tipoCatering</td>
                </tr>
                <tr>
                    <td>Persone: $numPersone</td>
                    <td>€ $prezzo/cad</td>
                </tr>
                <tr>
                    <td>Totale:</td>
                    <td></td>
                    <td><b>€ ".$prezzo * $numPersone."</b><td>
                </tr>
            </table></br>
            <p>daPin</p>";
            sendMail($utente,$titolo,$messaggio);
            $ORDINATO = TRUE;
        }
        else
        {
            $sql = "DELETE FROM Ordine WHERE ID = '$id'";
            $conn -> query($sql);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
    <title>da Pin</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="fontawesome/css/font-awesome.min.css" rel="stylesheet">
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
                                        <li><a class="dropdown-item" style="color:#59A0F8" href="carrello.php">Ordine</a></li>
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
                            <img src="../<?php echo $immagineProfilo?>" alt="accountLogo" style="border-radius:30px;" width="51" height="51"/>
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
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="card" style="border-radius: 1rem; max-width:700px; background-color:#F1F4F7">
                    <div class="card-body p-4 p-lg-5 text-black formRegister center">
                        <form class="menu output" method="post" action="">
                            <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Persone</h3>
                            <div class="row">
                                <div class="form-group col pb-2">
                                    <label>Numero di persone</label><input type="number" name="nPersone" class="form-control" value="10" min="10" max="30" required>
                                </div>
                            </div>
                            <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Data e ora</h3>
                            <div class="row">
                                <div class="form-group col pb-2">
                                    <input type="datetime-local" class="form-control" name="data" min="<?php echo $limit?>" required>
                                </div>
                            </div>
                            <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Metodo di pagamento</h3>
                            <div class="form-group col-md-6 pb-2">
                                <select class="form-control opzioni" name="pagamento" required>
                                    <?php
                                        $sql = "SELECT circuito FROM CartaCredito WHERE proprietario = '$email'";
                                        $result = $conn -> query($sql);
                                        $row = $result -> fetch_assoc();
                                        $circuito = $row["circuito"];
                                        echo '<option value="'.$circuito.'">'.ucfirst($circuito).'</option>';
                                    ?>
                                    <option value="contanti">Contanti</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 pb-2 ">
                                    <input type="submit" name="paga" value="Paga" class="opzioni btn btn-dark btn-lg btn-block mt-1">
                                </div>
                            </div>   
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>Ristorante</h4>
                    <div style="color:#ffffff">da Pin | Via Borzoli, 21 - 16153 Sestri Ponente (Genova) ITALIA | C.F. CLVPNI47R10D969Q | <br>P.Iva 123456789</div><br>
                    <a class="trans-color-text link" style="text-decoration: none" href="mailto:daPin.ristorante@gmail.com">daPin.ristorante@gmail.com </a>  <br><span itemprop="telefono:"><a class="trans-color-text link" style="text-decoration: none" href="tel:+390106508778">+39 010 650 8778</a></span><br>
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
    <!--Modal ordinazione-->
    <div class="modal fade" id="order" tabindex="-1" aria-labelledby="orderLabel" aria-hidden="true" style="color:black;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderLabel">Successo!</h5>
                    <a href="../index.php"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></a>
                </div>
                <div class="modal-body">
                    L'ordine è stato ricevuto, maggiori dettagli nella mail.
                </div>
                <div class="modal-footer">                               
                    <a href="../index.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button></a>
                </div>
            </div>
        </div>
    </div>
    <!--Modal ristorante occupato-->
    <div class="modal fade" id="busyRestaurant" tabindex="-1" aria-labelledby="busyRestaurantLabel" aria-hidden="true" style="color:black;" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="busyRestaurantLabel">Ci scusiamo per il disagio.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Nel giorno selezionato il ristorante non può ospitare altri catering.
                </div>
                <div class="modal-footer">                               
                    <a href="../index.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button></a>
                </div>
            </div>
        </div>
    </div>                       
    </body>   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>    
</html>
<?php
    if($ORDINATO)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#order").modal({backdrop: "static", keyboard: false});
                    $("#order").modal("show");
                });
            </script>';             
    }   
    if($STESSOGIORNO >= 2)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){                    
                    $("#busyRestaurant").modal("show");
                });
            </script>';          
    }    	 
?>