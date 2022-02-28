<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "daPin";
    $conn = mysqli_connect($servername , $username , $password , $dbName);
    if(!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
    $CONSEGNATO = TRUE;
    $codiceConsegna = $_GET["codice"];
    $sql = "SELECT * FROM Ordine WHERE codice = $codiceConsegna AND catering IS NULL";
    $result = $conn -> query($sql);
    if($result -> num_rows == 0)
    {
        $CONSEGNATO = FALSE;
    }
    if($CONSEGNATO)
    {
        $emailCliente = $_GET["utente"];
        $emailRider = $_GET["rider"];
        $sql = "SELECT * FROM Ordine WHERE cliente = '$emailCliente'";
        $result = $conn -> query($sql);
        while($row = $result -> fetch_assoc())
        {
            if($row["catering"] == NULL && $row["codice"] == $codiceConsegna)
            {
                $sql = "DELETE FROM Ordine WHERE cliente = '$emailCliente' AND codice = $codiceConsegna AND catering IS NULL";
                $conn -> query($sql);
            }
        }
        $sql = "SELECT * FROM Rider WHERE email = '$emailRider'";
        $result = $conn -> query($sql);
        while($row = $result -> fetch_assoc())
        {
            $stipendio = $row["stipendio"] + 5;
            $consegne = $row["consegne"] + 1;
        }
        $sql = "UPDATE Rider SET libero = 1 , stipendio = $stipendio , consegne = $consegne WHERE email = '$emailRider'";
        $conn -> query($sql);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>da Pin - Conferma ordine</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
        <link rel="icon" href="../img/pinIcon.png">
    </head>
    <body>
        <h1>Cosegna effettuata!</h1>
        <h2>Ora puoi chiudere questa pagina</h2>
    </body>
</html>