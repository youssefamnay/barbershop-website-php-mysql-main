
if(isset($_POST['submit_book_appointment_form']) && $_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Selected SERVICES

    $selected_services = $_POST['selected_services'];

    // Selected EMPLOYEE

    $selected_employee = $_POST['selected_employee'];

    // Selected DATE+TIME

    $selected_date_time = explode(' ', $_POST['desired_date_time']);

    $date_selected = $selected_date_time[0];
    $start_time = $date_selected." ".$selected_date_time[1];
    $end_time = $date_selected." ".$selected_date_time[2];


    //Client Details

    $client_first_name = test_input($_POST['client_first_name']);
    $client_last_name = test_input($_POST['client_last_name']);
    $client_phone_number = test_input($_POST['client_phone_number']);
    $client_email = test_input($_POST['client_email']);

    $con->beginTransaction();

    try
    {
        // Check If the client's email already exist in our database
        $stmtCheckClient = $con->prepare("SELECT * FROM clients WHERE client_email = ?");
        $stmtCheckClient->execute(array($client_email));
        $client_result = $stmtCheckClient->fetch();
        $client_count = $stmtCheckClient->rowCount();

        if($client_count > 0)
        {
            $client_id = $client_result["client_id"];
        }
        else
        {
            $stmtgetCurrentClientID = $con->prepare("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'barbershop' AND TABLE_NAME = 'clients'");

            $stmtgetCurrentClientID->execute();
            $client_id = $stmtgetCurrentClientID->fetch();

            $stmtClient = $con->prepare("insert into clients(first_name,last_name,phone_number,client_email) 
                        values(?,?,?,?)");
            $stmtClient->execute(array($client_first_name,$client_last_name,$client_phone_number,$client_email));
        }


        

        $stmtgetCurrentAppointmentID = $con->prepare("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'barbershop' AND TABLE_NAME = 'appointments'");

        $stmtgetCurrentAppointmentID->execute();
        $appointment_id = $stmtgetCurrentAppointmentID->fetch();
        
        $stmt_appointment = $con->prepare("insert into appointments(date_created, client_id, employee_id, start_time, end_time_expected ) values(?, ?, ?, ?, ?)");
        $stmt_appointment->execute(array(Date("Y-m-d H:i"),$client_id[0],$selected_employee,$start_time,$end_time));

        foreach($selected_services as $service)
        {
            $stmt = $con->prepare("insert into services_booked(appointment_id, service_id) values(?, ?)");
            $stmt->execute(array($appointment_id[0],$service));
        }
        
        echo "<div class = 'alert alert-success'>";
            echo "Super! Votre rendez-vous a été créé avec succès.";
        echo "</div>";

        $con->commit();
    }
    catch(Exception $e)
    {
        $con->rollBack();
        echo "<div class = 'alert alert-danger'>"; 
            echo $e->getMessage();
        echo "</div>";
    }
}
