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
        header("location: index.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
    <title>da Pin</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="fontawesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
        <link rel="icon" href="img/pinIcon.png">
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/pin4.png" alt="logo" width="90" height="84">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php""><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="pagine/info.php"><i class="fa fa-info"></i> Info</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-cutlery"></i>     Menu</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" style="color:#59A0F8" href="pagine/menu.php?tipo=menu">Visualizza Menu</a></li>
                            <li><a class="dropdown-item" style="color:#59A0F8" href="pagine/catering.php?tipo=menu">Visualizza Catering</a></li>
                            <?php
                                if(!empty($_SESSION["utente"]))
                                {
                                    echo '
                                        <li><a class="dropdown-item" style="color:#59A0F8" href="pagine/carrello.php">Ordine</a></li>                                
                                    ';
                                }
                            ?>
                        </ul>
                    </li>
                </ul>
                <ul class="nav justify-content-end">
                    <li class="nav-item dropstart">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:white;">
                            <img src="<?php echo $immagineProfilo;?>" alt="accountLogo" style="border-radius:30px;" width="51" height="51"/>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                                if(!empty($_SESSION["utente"]))
                                {
                                    echo '
                                        <li>
                                            <a class="dropdown-item" style="color:#59A0F8;" href="pagine/account.php"><i class="fa fa-user-circle"></i> Account</a>
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
                                                <a class="dropdown-item" style="color:#59A0F8" href="pagine/dashboardAdmin.php"><i class="fa fa-user-circle"></i> Dashboard</a>
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
                                                <a class="dropdown-item" style="color:#59A0F8" href="pagine/admin.php"><i class="fa fa-user-circle"></i> Area Staff</a>
                                            </li>                                        
                                            <li>
                                                <a class="dropdown-item" style="color:#59A0F8" href="pagine/login.php"><i class="fa fa-user-circle"></i> Login/Registrazione</a>
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
        <div id="carouselExampleIndicators2" class="carousel slide mt-2" data-bs-ride="carousel" style="max-width: 70rem;margin:auto;">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators2" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators2" data-bs-slide-to="1" aria-label="Slide 2"></button> 
                <button type="button" data-bs-target="#carouselExampleIndicators2" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators2" data-bs-slide-to="3" aria-label="Slide 4"></button>
                
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/interno1.jpg" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="img/genova6.jpeg" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="img/genova7.jpg" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="img/genova4.jpg" class="d-block w-100">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators2" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators2" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>                                     
        <div style="text-align: center;" class="mt-2">
            <div id="descrizione">
                <h1>Chi siamo</h1>
                <p style="font-size:20px;">Siamo un piccolo ristorante a Genova, la "Superba", una città stupenda, terra di tradizioni e sapori unici. </p>
            </div>
            <div id="mappa">
                <h1>Dove trovarci</h1>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2849.3062855322187!2d8.85355111570955!3d44.426879110074196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12d340b679ef5a85%3A0x9cce5a324ac66bd0!2sIstituto%20Italo%20Calvino!5e0!3m2!1sit!2sit!4v1643902132699!5m2!1sit!2sit" width="80%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>                             
            </div>
            <div id="menu" class="mb-2 ms-2 me-2">
                <h1>Menu</h1>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <div class="col">
                        <div class="card h-100 border-0">
                            <a href="pagine/menu.php?tipo=Antipasto"><img src="img/piatti/cuculli.jpg" class="card-img-top" alt="antipasti" height="209"></a>
                            <div class="card-body">
                                <h5 class="card-title" style="color:#59A0F8;">Antipasti</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-0">
                            <a href="pagine/menu.php?tipo=Primo"><img src="img/piatti/trofiealpestoconpatateefagiolini.jpg" class="card-img-top" alt="primi" height="209"></a>
                            <div class="card-body">
                                <h5 class="card-title" style="color:orange;">Primi</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-0">
                            <a href="pagine/menu.php?tipo=Secondo"><img src="img/piatti/cima.jpg" class="card-img-top" alt="secondi" height="209"></a>
                            <div class="card-body">
                                <h5 class="card-title" style="color:#59A0F8;">Secondi</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-0">
                            <a href="pagine/menu.php?tipo=Pizza"><img src="img/piatti/focacciadirecco.jpg" class="card-img-top" alt="pizzeefocacce" height="209"></a>
                            <div class="card-body">
                                <h5 class="card-title" style="color:orange;">Pizze e Focacce</h5>
                            </div>
                        </div>
                    </div>   
                    <div class="col">
                        <div class="card h-100 border-0">
                            <a href="pagine/menu.php?tipo=Dolce"><img src="img/piatti/pandolce.jpg" class="card-img-top" alt="dolci" height="209"></a>
                            <div class="card-body">
                                <h5 class="card-title" style="color:#59A0F8;">Dolci</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-0">
                            <a href="pagine/menu.php?tipo=Bevanda"><img src="img/piatti/corochinato.jpg" class="card-img-top" alt="bevande" height="209"></a>
                            <div class="card-body">
                                <h5 class="card-title" style="color:orange;">Bevande</h5>
                            </div>
                        </div>
                    </div>                              
                </div>                    
            </div>
        </div>
        <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>Ristorante</h4>
                    <div style="color:#ffffff">da Pin | Via Borzoli, 21 - 16153 Sestri Ponente (Genova) ITALIA | C.F. CLVPNI47R10D969Q | <br>P.Iva 123456789</div><br>
                    <a class="trans-color-text link" style="text-decoration: none" href="mailto:daPin.ristorante@gmail.com">daPin.ristorante@gmail.com<br><span itemprop="telefono:"><a class="trans-color-text link" style="text-decoration: none" href="tel:+390106508778">+39 010 650 8778</a></span><br>
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="pagine/info.php">Info</a></li>
                        <li><a href="pagine/menu.php?tipo=menu">Menu</a></li>
                        <li><a href="pagine/catering.php">Catering</a></li>
                        <li><a href="pagine/login.php">Login-Registrazione</a></li>
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
                        <a target="_blank" href="index.html"><img width="100" src="img/calvino3.png" alt="logo" style="border-radius: 20%;width:160px;height:70px;"></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>            
    </body>   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>      
</html> 