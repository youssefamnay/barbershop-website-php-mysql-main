<?php

    include "../functions/functions.php";
    
    if(isset($_POST['contact_name']) && isset($_POST['contact_email']) && isset($_POST['contact_subject']) && isset($_POST['contact_message']))
    {
        
        $contact_name = test_input($_POST['contact_name']);
        $contact_email  = test_input($_POST['contact_email']);
        $contact_subject = test_input($_POST['contact_subject']);
        $contact_message = test_input($_POST['contact_message']);        

        try
        {
            mail("votre email", $contact_subject, $contact_message);
            echo "<div class='alert alert-success'>";
                echo "Le message a été envoyé avec succès";
            echo "</div>";
        }
        catch(Exception $ex)
        {
            echo "<div class='alert alert-warning'>";
                echo "Un problème est survenu lors de l'envoi du message, veuillez réessayer !";
            echo "</div>";
        }

    }

?>
