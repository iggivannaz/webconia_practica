<?php
    function stringbereinigung($string){
        $string = trim($string); //Leerzeichenbereinigung
        $string = str_replace("_", "", $string);
        $string = str_replace(".", "", $string);
        $string = str_replace("+", "", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("*", "", $string);
        $string = str_replace("'", "", $string);
        $string = str_replace("(", "", $string);
        $string = str_replace(")", "", $string);
        $string = str_replace(",", "", $string);
        $string = str_replace("{", "", $string);
        $string = str_replace("}", "", $string);
        $string = str_replace("|", "", $string);
        $string = str_replace("^", "", $string);
        $string = str_replace("~", "", $string);
        $string = str_replace("[", "", $string);
        $string = str_replace("]", "", $string);
        $string = str_replace("`", "", $string);
        $string = str_replace("<", "", $string);
        $string = str_replace(">", "", $string);
        $string = str_replace("#", "", $string);
        $string = str_replace("%", "", $string);
        $string = str_replace('"', "", $string);
        $string = str_replace(";", "", $string);
        $string = str_replace("/", "", $string);
        $string = str_replace("?", "", $string);
        $string = str_replace(":", "", $string);
        $string = str_replace("@", "", $string);
        $string = str_replace("=", "", $string);
        $string = str_replace(".", "", $string);
        $string = str_replace("´", " ", $string);
        $string = filter_var($string, FILTER_SANITIZE_ADD_SLASHES); //addslashes();
        $string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW); //entferne /t/n/g/s
        return $string;
    }

    function upload_abbruch($why){
        if($why === 'email_already_exists'){
            $upload_meldung = 'Ihre eingegebene E-Mail-Adresse wurde bereits registriert. Für eine teilnehmende Person, kann nur eine E-Mail Adresse vergeben werden. Sollte es sich hier um einen Fehler handeln, melden Sie sich gerne telefonisch unter %2B49-40-609432309 oder per Mail unter info@webconia.de.';
            header('Location:./wtc_anmeldung.php?upload_meldung='.$upload_meldung);
            exit();
        }
        if($why === 'email_validation_failed'){
            $upload_meldung = 'Ihre eingegebene E-Mail-Adresse enstpricht leider keinem gängigen E-Mail-Format. Bitte prüfen Sie Ihre Eingabe und versuchen Sie es erneut.';
            header('Location:./wtc_anmeldung.php?upload_meldung='.$upload_meldung);
            exit();
        }
        if($why === 'sql_upload_failed' || $why === 'sql_connection_failed'){
            $upload_meldung = 'Wir bitten Sie um Entschuldigung. <br><br>Aufgrund eines technischen Problems sind WTC-Anmeldungen derzeit nicht möglich. <br>Versuchen Sie es bitte zu einem späteren Zeitpunkt erneut. <br><br>Alernativ können Sie sich gerne telefonisch unter %2B49-40-609432309 anmelden.';
            header('Location:./wtc_anmeldung.php?upload_meldung='.$upload_meldung);
            exit();
        }
    }

    $vorname = stringbereinigung($_POST['upload_vorname']);
    $nachname = stringbereinigung($_POST['upload_nachname']);
    $email = filter_var($_POST['upload_email'], FILTER_SANITIZE_EMAIL);
    $firma = stringbereinigung($_POST['upload_firma']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
        upload_abbruch('email_validation_failed');
    
    $con = @new mysqli('localhost', 'webconia', 'mariamaria#1111', 'webconia');
    if($con->connect_error)
        upload_abbruch('sql_connection_failed');   

    $query = "SELECT count(1) FROM wtc_teilnehmende WHERE email = ?";
    $mail_exist_check = $con->prepare($query);
    $mail_exist_check->bind_param('s', $email);
    $mail_exist_check->execute();
    $mail_exist_check->bind_result($found);
    $mail_exist_check->fetch();
    if ($found)
        upload_abbruch('email_already_exists');
    $mail_exist_check->close();

    $upload_query = $con->prepare("INSERT INTO wtc_teilnehmende"
        . "(vorname, nachname, email, firma)"
        . "VALUES(?, ?, ?, ?)");
    $upload_query->bind_param('ssss', $vorname, $nachname, $email, $firma); 
    $upload_query->execute();

    if($upload_query->affected_rows > 0){
        $upload_query->close();
        $con->close();
        //$email_text = "Hiermit bestätigen wir Ihre Anmeldung zum WTC 2022. <br><br>Dies ist eine automatisch erzeugte E-Mail, bitte antworten Sie nicht darauf.<br><br> Mit freundlichen Grüßen<br><br><br> Ihr webconia-Team";
        //$email_final_text = wordwrap($email_text, 70, "\r\n");
        //mail("$email", "Bestätigung Ihrer Anmeldung", "$email_final_text");
        $upload_meldung = 'Vielen Dank für Ihre Anmeldung zum WTC 2022. Wir freuen uns Sie bald begrüßen zu dürfen.';
        header("Location:./wtc_anmeldung.php?upload_meldung=".$upload_meldung);
        exit();       
    } 
        
    $upload_query->close();
    $con->close();
    upload_abbruch("sql_upload_failed");
    exit();

?>