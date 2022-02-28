<!--
    Pagina per aggiungere piatti, modificarli (anche i catering) e vedere il resoconto sui RIDER
-->
<?php
    $PIATTO = FALSE;
    $TIPOCATERING = FALSE;
    $ERRORE = FALSE;
    $BUTTONS = TRUE;
    $ADDMENU = FALSE;
    $MODIFYMENU = FALSE;
    $DELETEMENU = FALSE;
    $ADDCATERING = FALSE;
    $MODIFYCATERING = FALSE;
    $DELETECATERING = FALSE;
    $MANAGERIDERS = FALSE;
    $ADDRIDER = FALSE;
    $RIDER = FALSE;
    session_start();
    $utente = $_SESSION['admin'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "daPin";
    $conn = mysqli_connect($servername , $username , $password , $dbName);
    if(!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
    function searchPhoto($nomePiatto)
    {
        $fileexist = glob("../img/piatti/".strtolower(str_replace(' ','', $nomePiatto)).".*");
        if(!empty($fileexist))
        {
            foreach($fileexist as $fileToDelete)
            {
                unlink($fileToDelete);
            }
        }
    }

    if(isset($_POST['addPlate']))
    {
        //Controllo esistenza piatto
        $titolo = $_POST["titoloPiatto"];
        $sql = "SELECT * FROM Piatto WHERE nome = '$titolo'";
        $result = $conn -> query($sql);
		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_assoc())
			{
				if($row['nome'] == $titolo)
				{
					$PIATTO = TRUE;
					break;
				}
			}
		}
        if(!$PIATTO)
        {
            $ext = (pathinfo($_FILES['foto']['name']))['extension'];
            $folder = 'img/piatti/'.strtolower(str_replace(' ' , '' , $titolo)).".".$ext;
            move_uploaded_file($_FILES['foto']['tmp_name'] , "../".$folder);
            $descrizione = $_POST["descrizione"]; 
            $tipo = $_POST["tipo"];
            $prezzo = $_POST["prezzo"];
            $sql = "INSERT INTO Piatto(nome,tipo,descrizione,prezzo,immagine) VALUES('$titolo','$tipo','$descrizione',$prezzo,'$folder')";    
            if($conn -> query($sql) === FALSE)
            {
                $ERRORE = TRUE;
            }     
        }
    }
    if(isset($_POST['addCatering']))
    {
        //Controllo esistenza tipo catering
        $titolo = $_POST["titolo"];
        $sql = "SELECT * FROM Catering WHERE nome = '$titolo'";
        $result = $conn -> query($sql);
		if($result -> num_rows > 0)
		{
			while($row = $result -> fetch_assoc())
			{
				if($row['nome'] == $titolo)
				{
					$TIPOCATERING = TRUE;
					break;
				}
			}
		}
        if(!$TIPOCATERING)
        {
            $ext = (pathinfo($_FILES['foto']['name']))['extension'];
            $folder = 'img/piatti/'.strtolower(str_replace(' ' , '' , $titolo)).".".$ext;
            move_uploaded_file($_FILES['foto']['tmp_name'] , "../".$folder);
            $descrizione = $_POST["descrizione"]; 
            $tipo = $_POST["tipo"];
            $prezzo = $_POST["prezzo"];
            $sql = "INSERT INTO Catering(tipo,descrizione,prezzo,immagine,nome) VALUES('$tipo','$descrizione',$prezzo,'$folder','$titolo')";    
            if($conn -> query($sql) === FALSE)
            {
                $ERRORE = TRUE;
            }     
        }
    }
    if(isset($_POST['addRIDER']))
    {
        $nome = $_POST['nomeRIDER'];
        $cognome = $_POST['cognomeRIDER'];
        $email = $_POST['emailRIDER'];
        $sql = "SELECT * FROM Rider WHERE email = '$email'";
        $result = $conn -> query($sql);
        if($result -> num_rows > 0)
        {
            //Esiste già
            $RIDER = TRUE;
        }
        else
        {
            $sql = "INSERT INTO Rider(nome,cognome,email,consegne,stipendio,libero) VALUES('$nome','$cognome','$email',0,0,1)";
            $conn -> query($sql);            
        }
        $BUTTONS = FALSE;
        $MANAGERIDERS = TRUE;        
    }
    if(isset($_POST['removeRider']))
    {
        $rider = $_POST['rider'];
        $sql = "DELETE FROM Rider WHERE ID = $rider";
        if($conn -> query($sql) === FALSE)
        {
            $ERRORE = TRUE;
        }
        $BUTTONS = FALSE;
        $MANAGERIDERS = TRUE;
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
    if(isset($_POST['addMenu']))
    {
        $ADDMENU = TRUE;
        $BUTTONS = FALSE;
    }
    if(isset($_POST['modifyMenu']))
    {
        $MODIFYMENU = TRUE;
        $BUTTONS = FALSE;
    }
    if(isset($_POST['deleteMenu']))
    {
        $DELETEMENU = TRUE;
        $BUTTONS = FALSE;
    } 
    if(isset($_POST['addCatering2']))
    {
        $ADDCATERING = TRUE;
        $BUTTONS = FALSE;
    }
    if(isset($_POST['deleteCatering']))
    {
        $DELETECATERING = TRUE;
        $BUTTONS = FALSE;
    }
    if(isset($_POST['modifyCatering']))
    {
        $MODIFYCATERING = TRUE;
        $BUTTONS = FALSE;
    }
    if(isset($_POST['manageRIDERs']))
    {
        $MANAGERIDERS = TRUE;
        $BUTTONS = FALSE;
    }
    if(isset($_POST['addRIDERMenu']))
    {
        $ADDRIDER = TRUE;
        $BUTTONS = FALSE;
    }   
    if(isset($_POST['back']))
    {
        $BUTTONS = TRUE;
        $ADDMENU = FALSE;
        $MODIFYMENU = FALSE;
        $DELETEMENU = FALSE;
        $ADDCATERING = FALSE;
        $MODIFYCATERING = FALSE;
        $DELETECATERING = FALSE;
        $MANAGERIDERS = FALSE;
        $ADDRIDER = FALSE;
    }  
    if(isset($_POST['backRIDER']))
    {
        $BUTTONS = FALSE;
        $MANAGERIDERS = TRUE;
        $ADDRIDER = FALSE;        
    }
    if(isset($_POST['search']) || isset($_POST['searchDelete']))
    {
        $BUTTONS = FALSE;
    }
    if(isset($_POST['searchCatering']) || isset($_POST['searchDeleteCatering']))
    {
        $BUTTONS = FALSE;
    } 
    if(isset($_POST['modify']))
    {
        $nome = $_POST['titolo'];
        $descrizione = $_POST["descrizione"]; 
        $tipo = $_POST["tipo"];
        $prezzo = $_POST["prezzo"];
        if(empty(pathinfo($_FILES['foto']['name'] , PATHINFO_EXTENSION)))
        {
            $sql = "UPDATE Piatto SET nome = '$nome' , tipo = '$tipo' , descrizione = '$descrizione' , prezzo = $prezzo WHERE nome = '$nome'"; 
        }
        else
        {
            searchPhoto($nome);
            $ext = (pathinfo($_FILES['foto']['name']))['extension'];
            $folder = 'img/piatti/'.strtolower(str_replace(' ' , '' , $nome)).".".$ext;
            move_uploaded_file($_FILES['foto']['tmp_name'] , "../".$folder);  
            $sql = "UPDATE Piatto SET nome = '$nome' , tipo = '$tipo' , descrizione = '$descrizione' , prezzo = $prezzo , immagine = '$folder' WHERE nome = '$nome'"; 
        }   
        if($conn -> query($sql) === FALSE)
        {
            $ERRORE = TRUE;
        }        
    } 
    if(isset($_POST['delete']))
    {
        $nome = $_POST['titolo'];
        $sql = "DELETE FROM Piatto WHERE nome = '$nome'";
        if($conn -> query($sql) === FALSE)
        {
            $ERRORE = TRUE;
        }
    } 
    if(isset($_POST['modifyCateringInDB']))
    {
        $nome = $_POST['titolo'];
        $descrizione = $_POST["descrizione"]; 
        $tipo = $_POST["tipo"];
        $prezzo = $_POST["prezzo"];
        if(empty(pathinfo($_FILES['foto']['name'] , PATHINFO_EXTENSION)))
        {
            $sql = "UPDATE Catering SET nome = '$nome' , tipo = '$tipo' , descrizione = '$descrizione' , prezzo = $prezzo WHERE nome = '$nome'"; 
        }
        else
        {
            searchPhoto($nome);
            $ext = (pathinfo($_FILES['foto']['name']))['extension'];
            $folder = 'img/piatti/'.strtolower(str_replace(' ' , '' , $nome)).".".$ext;
            move_uploaded_file($_FILES['foto']['tmp_name'] , "../".$folder);  
            $sql = "UPDATE Catering SET nome = '$nome' , tipo = '$tipo' , descrizione = '$descrizione' , prezzo = $prezzo , immagine = '$folder' WHERE nome = '$nome'"; 
        }   
        if($conn -> query($sql) === FALSE)
        {
            $ERRORE = TRUE;
        }        
    }  
    if(isset($_POST['deleteCateringInDB']))
    {
        $nome = $_POST['titolo'];
        $sql = "DELETE FROM Catering WHERE nome = '$nome'";
        if($conn -> query($sql) === FALSE)
        {
            $ERRORE = TRUE;
        }
    }              
?>
<!DOCTYPE html>
<html>
    <head>
    <title>da Pin - Admin dashboard</title>
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
                                    <form method="post" action="">
                                        <input type="submit" style="color:#59A0F8" class="btn w-100" name="destroy" value="Esci">
                                    </form>
                                </li>  
                            </ul>
                        </li>                    
                    </ul>   
                </div>
            </div>
        </nav>  
        <div class="center mt-2">
            <div class="center">
                <?php
                    if($BUTTONS)
                    {
                        echo'<form method="post" class="formLogin" action="" style="margin-bottom:10px;">
                                Piatti:
                                <input type="submit" name="addMenu" value="Aggiungi" style="width:100px;height:35px;border-radius:5px;margin-bottom:10px;">
                                <input type="submit" name="modifyMenu" value="Modifica" style="width:100px;height:35px;border-radius:5px;margin-bottom:10px;">
                                <input type="submit" name="deleteMenu" value="Elimina" style="width:100px;height:35px;border-radius:5px;margin-bottom:10px;"><br>
                                Catering:
                                <input type="submit" name="addCatering2" value="Aggiungi" style="width:100px;height:35px;border-radius:5px;margin-bottom:10px;">
                                <input type="submit" name="modifyCatering" value="Modifica" style="width:100px;height:35px;border-radius:5px;margin-bottom:10px;">
                                <input type="submit" name="deleteCatering" value="Elimina" style="width:100px;height:35px;border-radius:5px;margin-bottom:10px;"><br>
                                Riders:
                                <input type="submit" name="manageRIDERs" value="Gestisci" style="width:100px;height:35px;border-radius:5px;margin-bottom:10px;">
                            </form>
                             
                        ';
                    }                 
                    if($ADDMENU)
                    {
                        echo'
                            
                            <section class="vh-100">
                                <div class="container py-5 h-100">
                                    <div class="row d-flex justify-content-center align-items-center h-100">
                                        <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                            <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                <form class="menu output" method="post" action="" enctype="multipart/form-data">
                                                    <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                    <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Aggiungi un piatto</h3>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 pb-2">
                                                            <input type="text" class="form-control" name="titoloPiatto" id="titolo" placeholder="Nome del piatto" required>
                                                        </div>
                                                        <div class="form-group col-md-6 pb-2">
                                                            <textarea class="form-control opzioni" id="descrizione" name="descrizione" rows="3" cols="50" class="mt-1" placeholder="Descrizione..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 pb-2">
                                                            <select class="form-control opzioni" name="tipo" id="tipo" required>
                                                                <option value="Antipasto">Antipasto</option>
                                                                <option value="Primo">Primo</option>
                                                                <option value="Secondo">Secondo</option>
                                                                <option value="Pizza">Pizza</option>
                                                                <option value="Dolce">Dolce</option>
                                                                <option value="Bevanda">Bevanda</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 pb-2">
                                                            <input type="number" class="form-control" name="prezzo" id="prezzo" placeholder="Prezzo" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">    
                                                        <div class="form-group col-md-12 pb-2">
                                                            <input type="file" class="form-control" name="foto" id="foto" placeholder="Inserisci Foto" accept="image/*" onchange="loadFile(event)" class="mt-1" required>
                                                            <img id="output" class="mt-1"/>
                                                        </div>   
                                                    </div>
                                                    <div class="form-group pb-2">
                                                        <input type="submit" name="addPlate" value="Inserisci" class="btn btn-dark btn-lg btn-block mt-1">
                                                    </div>
                                                </form>
                                                <form method="post" class="formLogin" action="">
                                                    <input type="submit" class="" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        ';
                    }
                    if($MODIFYMENU)
                    {
                        echo'
                        <section class="vh-100">
                                <div class="container py-5 h-100">
                                    <div class="row d-flex justify-content-center align-items-center h-100">
                                        <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                            <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                <form class="menu output" method="post" action="" enctype="multipart/form-data">
                                                    <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                    <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Modifica un piatto</h3>
                                                    <div class="row">
                                                        <div class="form-group col pb-2">
                                                            <label>Scrivi il nome del piatto che vuoi modificare</label>
                                                            <input type="text" class="form-control" name="nome" placeholder="Inserisci..." required>
                                                        </div>    
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 pb-2 ">
                                                            <input type="submit" name="search" value="Cerca" class="btn btn-dark btn-lg btn-block mt-1 opzioni">
                                                        </div>    
                                                    </div>
                                                    
                                                </form>
                                                <form method="post" class="formLogin" action="">
                                                    <input type="submit" class="" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>   
                        ';
                    }
                    if($DELETEMENU)
                    {
                        echo'
                        <section class="vh-100">
                                <div class="container py-5 h-100">
                                    <div class="row d-flex justify-content-center align-items-center h-100">
                                        <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                            <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                <form class="menu output" method="post" action="" enctype="multipart/form-data">
                                                    <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                    <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Elimina un piatto</h3>
                                                    <div class="row">
                                                        <div class="form-group col pb-2">
                                                            <label>Scrivi il nome del piatto che vuoi eliminare</label>
                                                            <input type="text" class="form-control" name="nome" placeholder="Inserisci..." required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 pb-2 ">
                                                            <input type="submit" name="searchDelete" value="Cerca" class="opzioni btn btn-dark btn-lg btn-block mt-1">
                                                        </div>
                                                    </div>
                                                    
                                                </form>
                                                <form method="post" class="formLogin" action="">
                                                    <input type="submit" class="" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>   
                        ';
                    }  
                    if($ADDCATERING)
                    {
                        echo'
                        <section class="vh-100">
                        <div class="container py-5 h-100">
                            <div class="row d-flex justify-content-center align-items-center h-100">
                                <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                    <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                        <form class="menu output" method="post" action="" enctype="multipart/form-data">
                                            <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                            <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Aggiungi un catering</h3>
                                            <div class="row">
                                                <div class="form-group col-md-6 pb-2">
                                                    <input type="text" class="form-control" name="titolo" id="titolo" placeholder="Titolo del catering" required>
                                                </div>
                                                <div class="form-group col-md-6 pb-2">
                                                    <textarea class="form-control opzioni" id="descrizione" name="descrizione" rows="4" cols="50" class="mt-1" placeholder="Descrizione..."></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6 pb-2">
                                                    <select class="form-control opzioni" name="tipo" id="tipo" required>
                                                        <option value="carne">Carne</option>
                                                        <option value="pesce">Pesce</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6 pb-2">
                                                    <input type="number" class="form-control" name="prezzo" id="prezzo" placeholder="Prezzo a persona" min="0" required>
                                                </div>
                                            </div>
                                            <div class="row">    
                                                <div class="form-group col-md-12 pb-2">
                                                    <input type="file" class="form-control" name="foto" id="foto" placeholder="Inserisci Foto" accept="image/*" onchange="loadFile(event)" class="mt-1" required>
                                                    <img id="output" class="mt-1"/>
                                                </div>   
                                            </div>
                                            <div class="form-group pb-2">
                                                <input type="submit" name="addCatering" value="Inserisci" class="btn btn-dark btn-lg btn-block mt-1">
                                            </div>
                                        </form>
                                        <form method="post" class="formLogin" action="">
                                            <input type="submit" class="" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>                   
                        ';
                    }
                    if($MODIFYCATERING)
                    {
                        echo'
                        <section class="vh-100">
                                <div class="container py-5 h-100">
                                    <div class="row d-flex justify-content-center align-items-center h-100">
                                        <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                            <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                <form class="menu output" method="post" action="" enctype="multipart/form-data">
                                                    <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                    <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Modifica un catering</h3>
                                                    <div class="row">
                                                        <div class="form-group col pb-2">
                                                            <label>Scrivi il nome del catering che vuoi modificare</label>
                                                            <input type="text" class="form-control" name="nome" placeholder="Inserisci..." required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 pb-2">
                                                            <input type="submit" name="searchCatering" value="Cerca" class="opzioni btn btn-dark btn-lg btn-block mt-1">
                                                        </div>
                                                    </div>
                                                    
                                                </form>
                                                <form method="post" class="formLogin" action="">
                                                    <input type="submit" class="" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>   
                        ';
                    }
                    if($DELETECATERING)
                    {
                        echo'
                        <section class="vh-100">
                                <div class="container py-5 h-100">
                                    <div class="row d-flex justify-content-center align-items-center h-100">
                                        <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                            <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                <form class="menu output" method="post" action="" enctype="multipart/form-data">
                                                    <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                    <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Elimina un catering</h3>
                                                    <div class="row">
                                                        <div class="form-group col pb-2">
                                                            <label>Scrivi il nome del catering che vuoi eliminare</label>
                                                            <input type="text" class="form-control" name="nome" placeholder="Inserisci..." required>
                                                        </div>    
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 pb-2 ">
                                                            <input type="submit" name="searchDeleteCatering" value="Cerca" class="opzioni btn btn-dark btn-lg btn-block mt-1">
                                                        </div>    
                                                    </div>
                                                    
                                                </form>
                                                <form method="post" class="formLogin" action="">
                                                    <input type="submit" class="" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>   
                        ';
                    }
                    if($MANAGERIDERS)
                    {
                        $sql = "SELECT * FROM Rider";
                        $result = $conn -> query($sql);
                        if($result -> num_rows > 0)
                        {
                            echo'
                            <div class="container">
                            <div class="row">
                            <table class="quickCart">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Cognome</th>
                                    <th>E-mail</th>
                                    <th>Consegne effettuate</th>
                                    <th>Stipendio</th>
                                    <th>Disponibile</th>
                                </tr>    
                        ';
                            while($row = $result -> fetch_assoc())
                            {
                                if($row["libero"] == 1)
                                {
                                    $string = "Disponibile";
                                }
                                else
                                {
                                    $string = "Non disponibile";
                                }
                                echo'
                                    <tr>
                                        <td>'.$row["ID"].'</td>
                                        <td>'.$row["nome"].'</td>
                                        <td>'.$row["cognome"].'</td>
                                        <td>'.$row["email"].'</td>
                                        <td>'.$row["consegne"].'</td>  
                                        <td>'.$row["stipendio"].'</td>
                                        <td>'.$string.'</td>
                                        <td>
                                            <form method="post" action="">
                                                <input type="hidden" value="'.$row["ID"].'" name="rider">    
                                                <input type="submit" class="delete" value="Licenzia" name="removeRider" style="border:none; background:none; font-weight: bold;">
                                            </form>
                                        </td>                                      
                                    </tr>
                                
                                ';
                            }
                        echo'
                            
                            </table>
                            </div>
                            <div class="row" style="margin-top:30px;">
                                <form method="post" class="formLogin" action="">
                                    <input type="submit" class="" name="addRIDERMenu" value="Aggiungi rider" style="width:150px;height:40px; border-radius:5px;margin-bottom:10px;">
                                    <input type="submit" class="" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                </form>
                            </div>
                            </div>
                        ';
                        }
                        else
                        {
                            echo '
                            
                                <form method="post" class="formLogin" action="">
                                    <a style="color:#0078af;">Non ci sono riders registrati.</a>&nbsp;&nbsp;
                                    <input type="submit" class="" name="addRIDERMenu" value="Aggiungi rider" style="width:150px;height:40px; border-radius:5px;margin-bottom:10px;">
                                    <input type="submit" class="" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                </form>
                            ';                               
                        }
                    }
                    if($ADDRIDER)
                    {
                        echo'
                        <section class="vh-100">
                                <div class="container py-5 h-100">
                                    <div class="row d-flex justify-content-center align-items-center h-100">
                                        <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                            <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                <form class="menu output" method="post" action="">
                                                    <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                    <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Aggiungi il rider</h3>
                                                    <div class="row">
                                                        <div class="form-group col pb-2">
                                                            <input type="text" class="form-control" name="nomeRIDER" placeholder="Nome" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col pb-2">
                                                            <input type="text" class="form-control" name="cognomeRIDER" placeholder="Cognome" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col pb-2">
                                                            <input type="text" class="form-control" name="emailRIDER" placeholder="E-mail" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 pb-2 ">
                                                            <input type="submit" name="addRIDER" value="Aggiungi" class="opzioni btn btn-dark btn-lg btn-block mt-1">
                                                        </div>
                                                    </div>
                                                    
                                                </form>
                                                <form method="post" class="formLogin" action="">
                                                    <input type="submit" class="" name="backRIDER" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>   
                        ';                        
                    }
                    if(isset($_POST['search']))
                    {
                        $nome = $_POST['nome'];
                        $sql = "SELECT * FROM Piatto WHERE nome = '$nome'";
                        $result = $conn -> query($sql);
                        if($result -> num_rows > 0)
                        {
                            while($row = $result -> fetch_assoc())
                            {
                                echo'
                                <section class="vh-100">
                                    <div class="container py-5 h-100">
                                        <div class="row d-flex justify-content-center align-items-center h-100">
                                            <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                                <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                    <form class="output" method="post" action="" enctype="multipart/form-data">
                                                        <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                        <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Modifica il piatto</h3>
                                                        <div class="row">
                                                            <div class="form-group col-md-12 pb-2">
                                                                <input type="file" name="foto" id="foto" accept="image/*" onchange="loadFile(event)" class="form-control mt-1">
                                                                <img src="../'.$row["immagine"].'" id="output" class="mt-1"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6 pb-2">
                                                                <input type="text" name="titolo" value="'.$row['nome'].'" class="form-control" required>
                                                            </div>
                                                            <div class="form-group col-md-6 pb-2">
                                                                <textarea id="descrizione" name="descrizione" rows="4" cols="50" class="form-control opzioni mt-1">'.$row["descrizione"].'</textarea>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="row"> 
                                                            <div class="form-group col-md-6 pb-2">
                                                                <input type="number" class="form-control" name="prezzo" id="prezzo" value="'.$row["prezzo"].'" min="0" required>
                                                            </div>   
                                                            <div class="form-group col-md-6 pb-2">
                                                                    <select class="form-control opzioni" name="tipo" id="tipo" required>';
                                                                        if($row["tipo"] == "Antipasto")
                                                                        {
                                                                            echo '
                                                                                    <option value="Antipasto" selected>Antipasto</option>
                                                                                    <option value="Primo">Primo</option>
                                                                                    <option value="Secondo">Secondo</option>
                                                                                    <option value="Pizza">Pizza</option>
                                                                                    <option value="Dolce">Dolce</option>
                                                                                    <option value="Bevanda">Bevanda</option>
                                                                                </select>                                            
                                                                            ';
                                                                        }
                                                                        if($row["tipo"] == "Primo")
                                                                        {
                                                                            echo '
                                                                                    <option value="Antipasto">Antipasto</option>
                                                                                    <option value="Primo" selected>Primo</option>
                                                                                    <option value="Secondo">Secondo</option>
                                                                                    <option value="Pizza">Pizza</option>
                                                                                    <option value="Dolce">Dolce</option>
                                                                                    <option value="Bevanda">Bevanda</option>
                                                                                </select>                                            
                                                                            ';
                                                                        }
                                                                        if($row["tipo"] == "Secondo")
                                                                        {
                                                                            echo '
                                                                                    <option value="Antipasto">Antipasto</option>
                                                                                    <option value="Primo">Primo</option>
                                                                                    <option value="Secondo" selected>Secondo</option>
                                                                                    <option value="Pizza">Pizza</option>
                                                                                    <option value="Dolce">Dolce</option>
                                                                                    <option value="Bevanda">Bevanda</option>
                                                                                </select>                                            
                                                                            ';
                                                                        }
                                                                        if($row["tipo"] == "Pizza")
                                                                        {
                                                                            echo '
                                                                                    <option value="Antipasto">Antipasto</option>
                                                                                    <option value="Primo">Primo</option>
                                                                                    <option value="Secondo">Secondo</option>
                                                                                    <option value="Pizza" selected>Pizza</option>
                                                                                    <option value="Dolce">Dolce</option>
                                                                                    <option value="Bevanda">Bevanda</option>
                                                                                </select>                                            
                                                                            ';
                                                                        }
                                                                        if($row["tipo"] == "Dolce")
                                                                        {
                                                                            echo '
                                                                                    <option value="Antipasto">Antipasto</option>
                                                                                    <option value="Primo">Primo</option>
                                                                                    <option value="Secondo">Secondo</option>
                                                                                    <option value="Pizza">Pizza</option>
                                                                                    <option value="Dolce" selected>Dolce</option>
                                                                                    <option value="Bevanda">Bevanda</option>
                                                                                </select>                                            
                                                                            ';
                                                                        }
                                                                        if($row["tipo"] == "Bevanda")
                                                                        {
                                                                            echo '
                                                                                    <option value="Antipasto">Antipasto</option>
                                                                                    <option value="Primo">Primo</option>
                                                                                    <option value="Secondo">Secondo</option>
                                                                                    <option value="Pizza">Pizza</option>
                                                                                    <option value="Dolce">Dolce</option>
                                                                                    <option value="Bevanda" selected>Bevanda</option>
                                                                                </select>                                            
                                                                            ';
                                                                        } 
                                                                    echo '</select>
                                                            </div>   
                                                        </div>';
                                                        echo '
                                                            <div class="form-group pb-2">
                                                                <input type="submit" name="modify" value="Modifica" class="btn btn-dark btn-lg btn-block mt-1">
                                                            </div>  
                                                    </form>
                                                    <form method="post" class="formLogin" action="">
                                                        <input type="submit" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                    </form>                                   
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section> 
                                ';
                            }
                        } 
                        else
                        {
                            echo '
                            <div class="form-group col pb-2">
                                <form method="post" class="formLogin" action="">
                                    <label>Nessun risultato</label>
                                    <input type="submit" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                </form>
                            </div>' ;

                        }                      
                    }
                    if(isset($_POST['searchCatering']))
                    {
                        $nome = $_POST['nome'];
                        $sql = "SELECT * FROM Catering WHERE nome = '$nome'";
                        $result = $conn -> query($sql);
                        if($result -> num_rows > 0)
                        {
                            while($row = $result -> fetch_assoc())
                            {
                                echo'
                                <section class="vh-100">
                                    <div class="container py-5 h-100">
                                        <div class="row d-flex justify-content-center align-items-center h-100">
                                            <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                                <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                    <form class="output" method="post" action="" enctype="multipart/form-data">
                                                        <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                        <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Modifica il catering</h3>
                                                        <div class="row">
                                                            <div class="form-group col-md-12 pb-2">
                                                                <input type="file" name="foto" id="foto" accept="image/*" onchange="loadFile(event)" class="form-control mt-1">
                                                                <img src="../'.$row["immagine"].'" id="output" class="mt-1"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6 pb-2">
                                                                <input type="text" name="titolo" value="'.$row['nome'].'" class="form-control" required>
                                                            </div>
                                                            <div class="form-group col-md-6 pb-2">
                                                                <textarea id="descrizione" name="descrizione" rows="4" cols="50" class="form-control opzioni mt-1">'.$row["descrizione"].'</textarea>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="row"> 
                                                            <div class="form-group col-md-6 pb-2">
                                                                <input type="number" class="form-control" name="prezzo" id="prezzo" value="'.$row["prezzo"].'" min="0" required>
                                                            </div>   
                                                            <div class="form-group col-md-6 pb-2">
                                                                <select class="form-control opzioni" name="tipo" id="tipo" required>';
                                                                        if($row["tipo"] == "carne")
                                                                        {
                                                                            echo '
                                                                                    <option value="carne" selected>Carne</option>
                                                                                    <option value="pesce">Pesce</option>
                                                                                </select>                                            
                                                                            ';
                                                                        }
                                                                        if($row["tipo"] == "pesce")
                                                                        {
                                                                            echo '
                                                                                    <option value="Carne">Carne</option>
                                                                                    <option value="Pesce" selected>Pesce</option>
                                                                                </select>                                            
                                                                            ';
                                                                        }       
                                                                echo '</select>  
                                                            </div>
                                                        </div>
                                                            <div class="form-group pb-2">
                                                                <input type="submit" name="modifyCateringInDB" value="Modifica" class="btn btn-dark btn-lg btn-block mt-1">
                                                            </div>  
                                                    </form>
                                                    <form method="post" class="formLogin" action="">
                                                        <input type="submit" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                    </form>                                   
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section> 
                                ';
                            }
                        } 
                        else
                        {
                            echo '
                            <div class="form-group col pb-2">
                                <form method="post" class="formLogin" action="">
                                    <label>Nessun risultato</label>
                                    <input type="submit" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                </form>
                            </div>' ;

                        }                      
                    }
                    if(isset($_POST['searchDelete']))
                    {
                        $nome = $_POST['nome'];
                        $sql = "SELECT * FROM Piatto WHERE nome = '$nome'";
                        $result = $conn -> query($sql);
                        if($result -> num_rows > 0)
                        {
                            while($row = $result -> fetch_assoc())
                            {
                                echo'
                                <section class="vh-100">
                                    <div class="container py-5 h-100">
                                        <div class="row d-flex justify-content-center align-items-center h-100">
                                            <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                                <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                    <form class="output" method="post" action="" enctype="multipart/form-data">
                                                        <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                        <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Elimina il piatto</h3>
                                                        <div class="row">
                                                            <div class="form-group col-md-12 pb-2">
                                                                <img src="../'.$row['immagine'].'" alt="'.$row['nome'].' id="output">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12 pb-2">
                                                                <h1>'.$row['nome'].'</h1>
                                                                <h2>'.$row['tipo'].'</h2>
                                                                <h3>'.$row['descrizione'].'</h3>
                                                                <h4>€ '.$row['prezzo'].'</h4>
                                                            </div>
                                                        </div>
                                                        <div class="row"> 
                                                            <div class="form-group col-md-6 pb-2">
                                                                <input type="hidden" class="form-control" name="titolo" value="'.$row['nome'].'">
                                                            </div>  
                                                        </div>
                                                        <div class="form-group pb-2">
                                                            <input type="submit" name="delete" value="Elimina" class="btn btn-dark btn-lg btn-block mt-1">
                                                        </div>  
                                                    </form>
                                                    <form method="post" class="formLogin" action="">
                                                        <input type="submit" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                    </form>                                   
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section> 
                                ';
                            }
                        } 
                        else
                        {
                            echo '
                            <div class="form-group col pb-2">
                                <form method="post" class="formLogin" action="">
                                    <label>Nessun risultato</label>
                                    <input type="submit" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                </form>
                            </div>' ;

                        }                      
                    }
                    if(isset($_POST['searchDeleteCatering']))
                    {
                        $nome = $_POST['nome'];
                        $sql = "SELECT * FROM Catering WHERE nome = '$nome'";
                        $result = $conn -> query($sql);
                        if($result -> num_rows > 0)
                        {
                            while($row = $result -> fetch_assoc())
                            {
                                echo'
                                <section class="vh-100">
                                    <div class="container py-5 h-100">
                                        <div class="row d-flex justify-content-center align-items-center h-100">
                                            <div class="card" style="border-radius: 1rem; max-width:700px;background-color:#F1F4F7">
                                                <div class="card-body p-4 p-lg-5 text-black formRegister center">
                                                    <form class="output" method="post" action="" enctype="multipart/form-data">
                                                        <img src="../img/pin4.png" alt="logo" width="190" height="94" style="position:center;">
                                                        <h3 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Elimina il catering</h3>
                                                        <div class="row">
                                                            <div class="form-group col-md-12 pb-2">
                                                                <img src="../'.$row['immagine'].'" alt="'.$row['nome'].' id="output">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12 pb-2">
                                                                <h1>'.$row['nome'].'</h1>
                                                                <h2>'.$row['tipo'].'</h2>
                                                                <h3>'.$row['descrizione'].'</h3>
                                                                <h4>€ '.$row['prezzo'].'</h4>
                                                            </div>
                                                        </div>
                                                        <div class="row"> 
                                                            <div class="form-group col-md-6 pb-2">
                                                                <input type="hidden" class="form-control" name="titolo" value="'.$row['nome'].'">
                                                            </div>  
                                                        </div>
                                                        <div class="form-group pb-2">
                                                            <input type="submit" name="deleteCateringInDB" value="Elimina" class="btn btn-dark btn-lg btn-block mt-1">
                                                        </div>  
                                                    </form>
                                                    <form method="post" class="formLogin" action="">
                                                        <input type="submit" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                                    </form>                                   
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section> 
                                ';
                            }
                        } 
                        else
                        {
                            echo '
                            <div class="form-group col pb-2">
                                <form method="post" class="formLogin" action="">
                                    <label>Nessun risultato</label>
                                    <input type="submit" name="back" value="Indietro" style="width:100px;height:40px; border-radius:5px;margin-bottom:10px;">
                                </form>
                            </div>' ;

                        }                      
                    }                                              
                ?> 
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
        <!--Modal piatto esistente-->
        <div class="modal fade" id="existingPlate" tabindex="-1" aria-labelledby="existingPlateLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existingPlateLabel">Attenzione!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Piatto già esistente!
                    </div>
                    <div class="modal-footer">                               
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div> 
        <!--Modal catering esistente-->
        <div class="modal fade" id="existingCtering" tabindex="-1" aria-labelledby="existingCateringLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existingCateringLabel">Attenzione!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Catering già esistente!
                    </div>
                    <div class="modal-footer">                               
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>  
        <!--Modal rider esistente-->
        <div class="modal fade" id="existingRider" tabindex="-1" aria-labelledby="existingRiderLabel" aria-hidden="true" style="color:black;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existingRiderLabel">Attenzione!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Rider già inserito!
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
    <script>
            var loadFile = function(event) {
                var image = document.getElementById('output');
                image.src = URL.createObjectURL(event.target.files[0]);
            };                      
        </script>          
</html>
<?php
    if($PIATTO)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#existingPlate").modal("show");
                });
            </script>';             
    }
    if($TIPOCATERING)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#existingCatering").modal("show");
                });
            </script>'; 
    }
    if($RIDER)
    {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#existingRider").modal("show");
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
?>