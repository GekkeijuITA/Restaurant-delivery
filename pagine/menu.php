<!--
    Pagina per i menu
-->
<?php
    $tipo = $_GET['tipo'];
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
    if(isset($_POST["add"]))
    {
        $nome = $_POST['name'];
        $sql = "SELECT * FROM Ordine WHERE cliente = '$email'";
        $result = $conn -> query($sql);
        //Il cliente ha già un piatto nell'ordine
        if($result -> num_rows > 0)
        {
            $codice = ((($conn -> query($sql))) -> fetch_assoc())["codice"];
            $sql = "SELECT * FROM Ordine WHERE nome = '$nome' AND cliente = '$email'";
            $result = $conn -> query($sql);
            if($result -> num_rows > 0)
            {
                //Il cliente aggiunge un piatto già ordinato
                $row = $result -> fetch_assoc();
                $quantita = $row["quantita"] + 1;
                $sql = "UPDATE Ordine SET quantita = $quantita WHERE nome = '$nome' AND cliente = '$email'";
            }
            else
            {
                $sql = "INSERT INTO Ordine(cliente,nome,quantita,codice) VALUES ('$email','$nome',1,$codice)";
            }
            $conn -> query($sql);
        }
        //Il cliente non ha mai ordinato
        else
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
            $sql = "INSERT INTO Ordine(cliente,nome,quantita,codice) VALUES ('$email','$nome',1,$codice)";
            $conn -> query($sql);
        }     
    }
    if(isset($_POST["remove"]))
    {
        $nome = $_POST['name'];
        $sql = "SELECT * FROM Ordine WHERE nome = '$nome' AND cliente = '$email'";
        $result = $conn -> query($sql);
        if($result -> num_rows > 0)
        {
            $row = $result -> fetch_assoc();
            $quantita = $row["quantita"] - 1;
            if($quantita > 0)
            {
                $sql = "UPDATE Ordine SET quantita = $quantita WHERE nome = '$nome' AND cliente = '$email'";
            }
            else
            {
                $sql = "DELETE FROM Ordine WHERE nome = '$nome' AND cliente='$email'";
            }
        }
        $conn -> query($sql);        
    }    
?>
<!DOCTYPE html>
<html>
    <head>
    <title>da Pin - <?php echo $tipo?></title>
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
                                <li><a class="dropdown-item" style="color:#59A0F8" href="catering.php?tipo=menu">Visualizza Catering</a></li>
                                <?php
                                    if(!empty($_SESSION["utente"]))
                                    {
                                        echo '
                                            <li><a class="dropdown-item" style="color:#59A0F8" href="carrello.php">Ordine</a></li>                                     
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
                                                <input type="submit" style="color:#59A0F8" class="btn w-100" name="destroy" value="Esci">
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
        <h1>
            <?php
                switch($tipo)
                {
                    case "Antipasto":
                        echo "Antipasti";
                        break;                    
                    case "Primo":
                        echo "Primi";
                        break;
                    case "Secondo":
                        echo "Secondi";
                        break;
                    case "Pizza":
                        echo "Pizze e Focacce";
                        break;
                    case "Dolce":
                        echo "Dolci";
                        break;
                    case "Bevanda":
                        echo "Bevande";
                        break;
                    case "menu":
                        echo "<p style='text-align:center;margin-top:20px;'>Menu da Pin</p>";
                        break;

                }
            ?>
        </h1>
        <?php
            function stampa($tipo,$conn,$titolo)
            {
                $sql = "SELECT * FROM Piatto WHERE tipo = '$tipo'";
                $result = $conn -> query($sql);
                if($result -> num_rows > 0)
                {
                    echo '<h2 class="ms-2">'.ucfirst($titolo).'</h2>';
                    echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-lg-6 g-4 mt-1 ms-2 me-2 mb-1" style="text-align: center;">';
                    while($row = $result -> fetch_assoc())
                    {
                        echo '
                            <div class="col">
                                <div style="border-radius:16px" class="card h-100">
                                    <a href="#"><img src="../'.$row["immagine"].'" class="card-img-top" alt="'.$row["nome"].'"></a>
                                    <div class="card-body">
                                        <h5 class="card-title">'.ucfirst($row["nome"]).'</h5>
                                        <p><b>'.$row["tipo"].'</b></p>
                                        <p>'.$row["descrizione"].'</p>
                                        <p>€'.$row["prezzo"].'</p>';
                                        if(!empty($_SESSION["utente"]))
                                        {
                                            echo'
                                                <form method="post" action="">
                                                    <input type="submit" name="remove" style="font-weight:bold;" class="btn btn-outline-danger" value="-">
                                                    <input type="submit" name="add" style="font-weight:bold;" class="btn btn-outline-success" value="+">
                                                    <input type="hidden" name="name" value="'.$row["nome"].'">
                                                </form> 
                                            ';                                      
                                            $cliente = $_SESSION["utente"];
                                            $nome = $row["nome"];
                                            $sql2 = "SELECT * FROM Ordine WHERE cliente = '$cliente' AND nome = '$nome'";
                                            $result2 = $conn -> query($sql2);
                                            if($result2 -> num_rows > 0)
                                            {
                                                $row = $result2 -> fetch_assoc();
                                                echo 
                                                '
                                                    Nel carrello: '.$row["quantita"].'
                                                ';
                                            }
                                            else
                                            {
                                                echo 'Nel carrello: 0';
                                            }
                                            
                                        }                                      
                        echo '      </div>
                                </div>
                            </div>
                        ';
                    }
                    echo '</div>';
                }
            }

            if($tipo == 'menu')
            {          
                stampa('antipasto',$conn,'Antipasti');   
                stampa('primo',$conn,'Primi'); 
                stampa('secondo',$conn,'Secondi'); 
                stampa('pizza',$conn,'Pizze e Focacce'); 
                stampa('dolce',$conn,'Dolci'); 
                stampa('bevanda',$conn,'Bevande');        
            }
            else
            {
                stampa($tipo,$conn,'');         
            }               
        ?>
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
        <div class="container-fluid">
            <?php
                if(!empty($utente))
                {
                    $sql = "SELECT * FROM Ordine WHERE cliente='$email'";
                    $badge = 0;
                    $result = $conn -> query($sql);
                    if($result -> num_rows > 0)
                    {
                        while($row = $result -> fetch_assoc())
                        {
                            if($row["catering"] == NULL)
                            {
                                $badge += $row['quantita'];
                            }
                        }
                    }
                    echo '
                        <button class="navbar-toggler bottom-right check" style="border-radius:50%;" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                            <span class="badge">'.$badge.'</span>
                        </button>                    
                    ';
                }
            ?>           
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Carrello di <?php echo $utente;?></h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <?php
                                $CARRELLO = FALSE;
                                $sql = "SELECT * FROM Ordine WHERE cliente = '$email'";
                                $result = $conn -> query($sql);
                                if($result -> num_rows > 0)
                                {
                                    while($row = $result -> fetch_assoc())
                                    {
                                        if($row["catering"] == NULL)
                                        {   
                                            $CARRELLO = TRUE;
                                        }
                                    }
                                    if($CARRELLO)
                                    {
                                        echo'
                                        <table class="quickCart">
                                            <tr>
                                                <th>Nome</th>
                                                <th>Quantità</th>
                                                <th>Prezzo</th>
                                            </tr>
                                        ';
                                        $totale = 0;
                                        $sql = "SELECT * FROM Ordine WHERE cliente = '$email'";
                                        $result = $conn -> query($sql);
                                        while($row = $result -> fetch_assoc())
                                        {
                                            if($row["catering"] == NULL)
                                            {
                                                $nome = $row["nome"];
                                                $sql = "SELECT * FROM Piatto WHERE nome = '$nome'";
                                                $prezzo = ((($conn -> query($sql)) -> fetch_assoc())["prezzo"]) * $row["quantita"];
                                                $totale += $prezzo;
                                                echo '
                                                    <tr>
                                                        <td>'.$nome.'</td>
                                                        <td>'.$row["quantita"].'</td>
                                                        <td>€ '.$prezzo.'</td>
                                                    </tr>
                                                ';
                                            }
                                        }
                                        echo '</table>';
                                        echo '<h3 class="mt-3" style="position:center;">Totale € '.$totale.'</h3>';
                                        echo '<a href="carrello.php"><button class="mt-2 check" style="width:120px;height:40px; border-radius:5px;margin-bottom:10px;">Check-out</button></a>';
                                    }
                                    else
                                    {
                                        echo "Non hai ancora inserito niente";
                                    }
                                }
                                else
                                {
                                    echo "Non hai ancora inserito niente";
                                }
                            ?>           
                      </li>
                    </ul>
                </div>
            </div>
        </div>             	
    </body>         
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>      
</html>