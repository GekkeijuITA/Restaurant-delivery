<?php
    $LONTANO = FALSE;
    $CAPSBAGLIATO = FALSE;
    $TELEFONO = FALSE;
    $EMAIL = FALSE;
    session_start(); 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "daPin";
    $conn = mysqli_connect($servername , $username , $password , $dbName);       
    if(!empty($_SESSION["admin"]))
    {
        $utente = $_SESSION["admin"];
        $sql = "SELECT * FROM Admin";
        $result = $conn -> query($sql);
        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                if($row['email'] == $utente)
                {
                    $utente = $row['nome'];
                    break;
                }
            }
        }        
    }
    else if(!empty($_SESSION["utente"]))
    {
        $utente = $_SESSION["utente"];
        $sql = "SELECT * FROM Cliente";
        $result = $conn -> query($sql);
        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                if($row['email'] == $utente)
                {
                    $email = $row['email'];
                    $utente = $row['nome']; 
                    $cognome = $row['cognome'];
                    $telefono = $row['nTelefono'];
                    $profilo = $row['immagine'];
                    $sql = "SELECT * FROM CartaCredito WHERE proprietario = '$email'";
                    $result = $conn -> query($sql);
                    if($result -> num_rows > 0)
                    {
                        while($row = $result -> fetch_assoc())
                        {
                            $ncarta = $row["nCarta"];
                            $dataScadenza = $row["dataScadenza"];
                            $dataEsplosa = explode("-",$dataScadenza);
                            $year = $dataEsplosa[0];
                            $month = $dataEsplosa[1];
                            break;
                        }
                    }
                    else
                    {
                        $ncarta = "Non hai carte registrate";
                    }
                    break;
                }
            }
        }
        $sql = "SELECT * FROM Indirizzo WHERE utente = '$email'";
        $result = $conn -> query($sql);
        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $via = $row['via'];
                $civico = $row['civico'];
                $interno = $row['interno'];
                $cap = $row['cap'];
            }
        }
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
    if(isset($_POST['addImage']))
    {
        $fileexist = glob("../img/account/".strtolower(str_replace('.','', $email)).".*");
        if(!empty($fileexist))
        {
            foreach($fileexist as $fileToDelete)
            {
                unlink($fileToDelete);
            }
        }
        $ext = (pathinfo($_FILES['accountImg']['name']))['extension'];
        $folder = 'img/account/'.strtolower(str_replace('.','', $email)).".".$ext;
        move_uploaded_file($_FILES['accountImg']['tmp_name'] , "../".$folder);
        $sql = "UPDATE Cliente SET immagine = '$folder' WHERE email = '$email' ";
        $conn -> query($sql);
        header("location: account.php");
    }  
    if(isset($_POST['modifyAddress']))
    {
        $citta = $_POST["citta"];
        if(strtolower($citta) == 'genova')
        {
            $cap = $_POST["cap"];
            if($cap >= 16121 && $cap <= 16167 || $cap == 16338)
            {
                $via = $_POST["via"];
                $civico = $_POST["civico"];
                $interno = $_POST["interno"];
        
                $sql = "UPDATE Indirizzo SET via = '$via' , civico = $civico, cap = $cap , interno = $interno WHERE utente = '$email'";
                $conn -> query($sql);
                header("location: account.php");
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
    if(isset($_POST['modifyPhone']))
    {
        $nTelefono = $_POST["telefono"];
        $sql = "SELECT * FROM Cliente";
        $result = $conn -> query($sql);
        while($row = $result -> fetch_assoc())
        {
            if($row["nTelefono"] == $nTelefono)
            {
                $TELEFONO = TRUE;
                break;
            }
        }
        if(!$TELEFONO)
        {
            $sql = "UPDATE Cliente SET nTelefono = '$nTelefono' WHERE email = '$email'";
            $conn -> query($sql);
            header("location: account.php");
        }
    } 
    if(isset($_POST['modifyEmail']))
    {
        $nuovaMail = $_POST['email'];
        $sql = "SELECT * FROM Cliente";
        $result = $conn -> query($sql);
        while($row = $result -> fetch_assoc())
        {
            if($row["email"] == $nuovaMail)
            {
                $EMAIL = TRUE;
                break;
            }
        }
        if(!$EMAIL)
        {
            $sql = "UPDATE Cliente SET email = '$nuovaMail' WHERE email = '$email'";
            $conn -> query($sql);
            $sql = "UPDATE Indirizzo SET utente = '$nuovaMail' WHERE utente = '$email'";
            $conn -> query($sql);
            $sql = "UPDATE CartaCredito SET proprietario = '$nuovaMail' WHERE proprietario = '$email'";
            $conn -> query($sql);
            $_SESSION["utente"] = $nuovaMail;
            header("location: account.php");
        }
    }
    if(isset($_POST['addCreditCard']))
    {
        $input = $_POST["scadenza"]."-01";
        $nCarta = $_POST["nCarta"];
        $circuito = $_POST["circuito"];
        $sql = "UPDATE CartaCredito SET nCarta = '$nCarta' , dataScadenza = '$input' , circuito = '$circuito'  WHERE proprietario = '$email'";
        $conn -> query($sql);
        header("location: account.php");
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
        <link href="../fontawesome/css/font-awesome.min.css" rel="stylesheet">
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
                                <img src="../<?php echo $profilo?>" alt="accountLogo" style="border-radius:30px;" width="51" height="51"/>
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
                                                <a class="dropdown-item" style="color:#59A0F8" href="account.php"><i class="fa fa-user-circle"></i> Account</a>
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
        <div class="center">
            <div style="border-radius:50px;background-color:#F1F4F7;margin-top:20px;margin-bottom:20px;max-width:380px;" class="card h-100">
                <div class="card-body" id="utente" style="color:#59A0F8;">                                            
                    <?php echo "
                        <button  type='button' class='btn'data-bs-toggle='modal' data-bs-target='#profileImg'><img src='../$profilo' alt='accountLogo' style='width:70px;height:70px;border-radius:40px;'/></button>
                        <p><h5>".$utente." ".$cognome."</h5></p>
                        <button type='button' class='btn' data-bs-toggle='modal' data-bs-target='#profileAddress'><p><i class='fa fa-map-marker' aria-hidden='true'></i>&nbsp;&nbsp;via ".$via." ".$civico."/".$interno."</p></button>
                        <button type='button' class='btn' data-bs-toggle='modal' data-bs-target='#profilePhone'><p><i class='fa fa-phone' aria-hidden='true'></i>&nbsp;&nbsp;".$telefono."</p></button>
                        <button type='button' class='btn' data-bs-toggle='modal' data-bs-target='#profileEmail'><p><i class='fa fa-envelope' aria-hidden='true'></i>&nbsp;&nbsp;".$email."</p></button>
                        <button type='button' class='btn' data-bs-toggle='modal' data-bs-target='#profileCreditCard'><p><i class='fa fa-credit-card-alt' aria-hidden='true'></i>&nbsp;&nbsp;".$ncarta."</p></button>
                    "
                    ?>
                </div>
            </div> 
        </div> 
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
                            <a target="_blank" href="../index.php"><img width="100" src="../img/calvino3.png" alt="logo" style="border-radius: 20%;width:160px;height:70px;"></a> 
                        </div>
                    </div>
                </div>
            </div>
        </footer> 
        <!--Modal immagine profilo-->
        <div class="modal fade" id="profileImg" tabindex="-1" aria-labelledby="profileImgLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileImgLabel">Scegli immagine profilo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" class="formRegister" enctype="multipart/form-data">
                        <div class="modal-body">
                                <input type="file" class="form-control" name="accountImg" id="foto" placeholder="Inserisci Foto" accept="image/*" onchange="loadFile(event)" class="mt-1" required>
                                <img id="output" class="mt-1"/>
                        </div>
                        <div class="modal-footer">                               
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                            <input type="submit" name="addImage" class="btn btn-primary" value="Inserisci">            
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Modal indirizzo-->
        <div class="modal fade" id="profileAddress" tabindex="-1" aria-labelledby="profileAddressLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileAddressLabel">Modifica indirizzo di consegna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" class="formRegister" action="">
                        <div class="modal-body">
                            <label>Via</label>
                            <input type="text" class="form-control" name="via" placeholder="Via" value="<?php echo $via?>" required>
                            <label>Civico</label>
                            <input type="text" class="form-control" pattern="[0-9]+" name="civico" placeholder="Civico" value="<?php echo $civico?>" required>
                            <label>Interno</label>
                            <input type="text" class="form-control" pattern="[0-9]+" name="interno" placeholder="Interno" value="<?php echo $interno?>" required>
                            <label>CAP</label>
                            <input type="text" class="form-control" pattern="[0-9]+" name="cap" placeholder="CAP" minlength="5" maxlength="5" value="<?php echo $cap?>" required>
                            <label>Città</label>
                            <input type="text" class="form-control" name="citta" placeholder="Città" required>
                        </div>
                        <div class="modal-footer">                               
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                            <input type="submit" name="modifyAddress" class="btn btn-primary" value="Modifica">            
                        </div>
                    </form>
                </div>
            </div>
        </div> 
        <!--Modal telefono-->
        <div class="modal fade" id="profilePhone" tabindex="-1" aria-labelledby="profilePhoneLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profilePhoneLabel">Modifica numero di telefono</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" class="formRegister" action="">
                        <div class="modal-body">
                            <label>Numero di telefono</label>
                            <input type="tel" class="form-control" name="telefono" placeholder="Numero di telefono" value="<?php echo $telefono?>" required>
                        </div>
                        <div class="modal-footer">                               
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                            <input type="submit" name="modifyPhone" class="btn btn-primary" value="Modifica">            
                        </div>
                    </form>
                </div>
            </div>
        </div> 
        <!--Modal email-->
        <div class="modal fade" id="profileEmail" tabindex="-1" aria-labelledby="profileEmailLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileEmailLabel">Modifica indirizzo e-mail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" class="formRegister" action="">
                        <div class="modal-body">
                            <label>E-mail</label>
                            <input type="email" class="form-control" name="email" placeholder="Indirizzo e-mail" value="<?php echo $email?>" required>
                        </div>
                        <div class="modal-footer">                               
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                            <input type="submit" name="modifyEmail" class="btn btn-primary" value="Modifica">            
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div> 
        <!--Modal carta di credito-->
        <div class="modal fade" id="profileCreditCard" tabindex="-1" aria-labelledby="profileCreditCardLabel" aria-hidden="true" style="color:black;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileCreditCardLabel">Modifica carta di credito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" class="formRegister" action="">
                    <div class="modal-body">
                    <label>Numero cartaCredito</label>
                    <input type="text" class="form-control" pattern="[0-9]+" name="nCarta" placeholder="Numero cartaCredito" value="<?php echo $ncarta?>" required>
                    <label>Data di scadenza della carta</label>
                    <input type="month" class="form-control" name="scadenza" placeholder="Data di scadenza della carta" value="<?php echo $year."-".$month?>" required>
                    <label>circuito</label>
                    <select class="form-control opzioni" name="circuito" required>
                        <?php
                            if(empty($circuito))
                            {
                                echo '
                                <option>Visa</option>
                                <option>Mastercard</option>
                                <option>Paypal</option>                                        
                            ';                                
                            }
                            else 
                            {
                                switch($circuito)
                                {
                                    case "Visa":
                                        echo '
                                            <option selected>Visa</option>
                                            <option>Mastercard</option>
                                            <option>Paypal</option>                                        
                                        ';
                                        break;
                                    case "Mastercard":
                                        echo '
                                            <option>Visa</option>
                                            <option selected>Mastercard</option>
                                            <option>Paypal</option>                                        
                                        ';
                                        break;
                                    case "Paypal":
                                        echo '
                                            <option>Visa</option>
                                            <option>Mastercard</option>
                                            <option selected>Paypal</option>                                        
                                        ';
                                        break;
                                }
                            }
                        ?>

                    </select>
                    <div class="modal-footer">                               
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                        <input type="submit" name="addCreditCard" class="btn btn-primary" value="Modifica">            
                    </div>
                </form>
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
        <!--Modal telefono esistente-->
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
        <!--Modal email esistente-->
        <div class="modal fade" id="email" tabindex="-1" aria-labelledby="distantLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="distantLabel">Attenzione!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Questa email è già stata usata!
                    </div>
                    <div class="modal-footer">                               
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>  
        <!--Modal cap sbagliato-->
        <div class="modal fade" id="cap" tabindex="-1" aria-labelledby="phoneLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="phoneLabel">Attenzione!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Questo CAP è sbagliato o non è della città di Genova!
                    </div>
                    <div class="modal-footer">                               
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>   
    </body>        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>      
</html>
<?php
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
    if($CAPSBAGLIATO)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#cap").modal("show");
                });
            </script>';             
    }
    if($EMAIL)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#email").modal("show");
                });
            </script>';             
    } 
?>