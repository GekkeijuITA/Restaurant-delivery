<!--
    Pagina delle informazioni
-->
<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "daPin";
    $conn = mysqli_connect($servername , $username , $password , $dbName);    
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
        <div class="table-responsive">
            <table class="table table-borderless" id="info" style="text-align:center">
                <tbody>
                    <tr style="background-color:white;">
                        <td><h1 style="margin-top:20px;">Qualcosa su di noi</h1></br>
                            <div id="infoRistorante">
                            <p>Siamo un piccolo ristorante a Genova, la "Superba", una città stupenda, terra di tradizioni e sapori unici.</p> 
                            <p>Il nostro locale è il luogo giusto se si vuole provare e assaporare tutte le prelibatezze della nostra terra e non solo!</br> 
                            Offriamo una serie di prodotti tipici sia a pranzo che a cena,</br> ti trovi nel posto giusto anche se vuoi mangiare qualcosa di veloce ma sempre di ottima qualità!</p></br>
                            <p>Ci puoi trovare in <a style="text-decoration: none; color:#59A0F8"href="https://www.google.com/maps/place/Istituto+Italo+Calvino/@44.426875,8.85574,16z/data=!4m5!3m4!1s0x0:0x9cce5a324ac66bd0!8m2!3d44.4268753!4d8.8557398?hl=it">via Borzoli, 21, 16153 Genova GE</a></p>
                        </div></td>
                        <td><div id="imgRistorante"><img src="../img/interno1.jpg" style="width:80%;max-width:70rem;border-radius:10px;margin-top:30px;vertical-align:middle;"></img></div></td>
                    </tr>
                    <tr style="background-color:white;">
                        <td><div id="infoCovidFoto"><img src="../img/covid19.png" style="width:60%;max-width:70rem;border-radius:10px;margin-top:30px;vertical-align:middle;" ></img></div></td>
                        <td><h1 style="margin-top:20px;">Come ti proteggiamo dal Covid-19</h1><br>
                            <div id="infoCovid">
                            <p>Il nostro locale segue e rispetta tutte le norme che vengono imposte dalla regione per contenere la diffusione del COVID-19 </br>
                            e permetterci di proseguire con la nostra attività.</p>
                            <p>Chiediamo a tutta la gentile clientela di aiutarci e di rispettare queste piccole ma fondamentali regole che ci vengono imposte.</p>                     
                        </div></td>
                        
                    </tr>
                    <tr style="background-color:white;">
                        <td><div id="infoOrari" style="margin-top:50px">
                            <h1>Orari</h1>  
                            <p>Lunedì - Sabato 11.00-15.00/19.00-23.00</p>   
                            <p>Domenica 12.00-15.00</p> 
                        </div></td>
                        <td><div id="infoSocial" style="margin-top:50px">
                            <h1>Social</h1>
                            <p>Potete contattarci e seguirci anche su i social.</p>
                            <a href="https://wa.me/390106508778"><img src="../img/whatsapp6.png" alt="WhatsApp" width="90" height="74"></a>&nbsp;&nbsp;&nbsp;
                            <a href="https://www.instagram.com/"><img src="../img/insta2.png" alt="Instagram" width="95" height="80"></a>&nbsp;&nbsp;&nbsp;
                            <a href="https://www.facebook.com"><img src="../img/facebook2.png" alt="Facebook" width="105" height="90"></a>
                        </div> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>      
</html>