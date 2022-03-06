<?php
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
        header("location: ../index.php");
    }
    if(!empty($_SESSION["utente"]))
    {
        $email = $_SESSION["utente"];
        $sql = "SELECT nome FROM Cliente WHERE email = '$email'";
        $result = $conn -> query($sql);
		if($result -> num_rows > 0)
		{
            $row = $result -> fetch_assoc();
            $utente = $row["nome"];
		}
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
?>
<!DOCTYPE html>
<html>
    <head>
    <title>da Pin - Check Out</title>
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
                                <li><a class="dropdown-item" style="color:#59A0F8" href="catering.php">Visualizza Catering</a></li>
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
                                                <input type="submit" style="color:#59A0F8" class="btn w-100" name="destroy" value="Esci">
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
        <?php
            $PIATTI = FALSE;
            $sql = "SELECT * FROM Ordine WHERE cliente = '$email'";
            $result = $conn -> query($sql);
            if($result -> num_rows > 0)
            {
                echo '
                    <div class="container">
                        <h1>Carrello</h1>
                        <div class="row mt-3">
                        <div class="col-3"></div>
                        <div style="color:orange" class="col-3">Piatto</div>
                        <div style="color:orange" class="col-3">Quantità</div>
                        <div style="color:orange" class="col-3">Prezzo</div>
                    </div>
                ';
                $totale = 0;
                $numArticoli = 0;
                $spedizione = 0;
                while($row = $result -> fetch_assoc())
                {
                    if($row["catering"] == NULL)
                    {
                        $PIATTI = TRUE;
                        $nome = $row["nome"];
                        $sql = "SELECT * FROM Piatto WHERE nome = '$nome'";
                        $quantita = $row["quantita"];
                        $numArticoli += $quantita;
                        $prezzo = ((($conn -> query($sql)) -> fetch_assoc())["prezzo"]);
                        $immagine = "../".(($conn -> query($sql)) -> fetch_assoc())["immagine"];
                        $totale += $prezzo * $quantita;
                        $spedizione = $totale + 2.5;
                        $_SESSION["totale"] = $spedizione;
                        echo '
                            <div class="row mt-3">
                                <div class="col-3 mt-2"><img src="'.$immagine.'" width="75%" style="border-radius:10px;"></div>
                                <div class="col-3">'.$nome.'</br><b>€ '.$prezzo.'</b></div>
                                <div class="col-3">'.$quantita.'</div>
                                <div class="col-3"><b>€ '.$prezzo * $quantita.'</b></div>
                            </div>
                        ';
                    }
                }
            }
            if($PIATTI)
            {
                echo '
                <div class="checkout">
                    <p>'.$numArticoli.' Articoli € '.$totale.'</p>
                    <p>Spedizione € 2.50</p>
                    <h3>Totale € '.$spedizione.'</h3>
                    <form method="post" class="formLogin" action="checkout.php">
                        <input type="submit" style="width:250px;height:40px; border-radius:5px;margin-bottom:10px;" value="Procedi con il pagamento"></input>
                    </form>
                </div>
                ';
            }           
            else
            {
                echo '
                <div class="form-group col pb-2">
                    <form method="post" class="formLogin " action="menu.php?tipo=menu">
                        <label>Carrello vuoto. Vai al menu per ordinare</label>
                        
                        <input type="submit" name="back" value="Menu" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                    </form>
                </div>' ;
            }
        ?> 
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