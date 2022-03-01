<div id="wtc-upload-form" class="whity">
    <div class="upload-meldung">
        <?php
            if($_GET['upload_meldung'])
                echo $_GET['upload_meldung'];    
        ?>
    </div>
    <h2>webconia technology conference</h2> 
    <div class="wtc_table">
        <form name="uploadformular" action="wtc_upload.php" method="post">
            <table>
                <caption>Ihre Anmeldung zur wtc-2022</caption>
                <tbody>  
                    <tr>
                        <td><label for="abfrage_vorname">Ihr Vorname: </label></td>
                        <td><input id="abfrage_vorname" type="text" name="upload_vorname" required></td>
                    </tr>
                    <tr>
                        <td><label for="abfrage_nachname">Ihr Nachname: </label></td>
                        <td><input id="abfrage_nachname" type="text" name="upload_nachname" required></td>
                    </tr>
                    <tr>
                        <td><label for="abfrage_email">Ihre E-Mail-Adresse: </label></td>
                        <td><input id="abfrage_email" type="e-mail" name="upload_email" required></td>
                    </tr>
                    <tr>
                        <td><label for="abfrage-firma">Ihr Firmenname: </label></td>
                        <td><input id="abfrage-firma" type="text" name="upload_firma" required></td>
                    </tr>
                </tbody>
            </table>
            <input 
                type="submit" name="submit" value="Anmeldung bestätigen"
                onclick="confirm('Möchten Sie sich mit den angegebenen Daten für das WTC-2022 anmelden?')"
            >
        </form>
    </div>
</div><!--#wtc-ploadformular-->
