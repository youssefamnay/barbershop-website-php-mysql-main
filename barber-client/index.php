<?php 
session_start();
if (isset($_SESSION['user_id'])) {
    $titreDePage = 'Tableau de bord';
    include 'connect.php';
    include 'functions/functions.php'; 
    include 'templates/header.php';
    $client_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titreDePage; ?></title>
    <style>
  .responsive-map{
overflow: hidden;
padding-bottom:56.25%;
position:relative;
height:0;
}
.responsive-map iframe{
left:0;
top:0;
height:100%;
width:100%;
position:absolute;
}

        /* Add your CSS styling here */
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table, .th, .td {
            border: 1px solid #ddd;
        }
        .th, .td {
            padding: 8px;
            text-align: left;
        }
        .th {
            background-color: #f2f2f2;
        }
        .ongoing {
            background-color: #dff0d8;
        }
        .completed {
            background-color: #d9edf7;
        }
        .canceled {
            background-color: #f2dede;
        }
    </style>

</head>
<body>
<div class="container-fluid">
		
		<!-- Titre de la page -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Mes reservations</h1>
            <a href="reserver.php" class="d-none d-sm-inline-block btn btn-lg btn-primary shadow-sm">
    <i class="fas fa-calendar fa-sm text-white-50"></i>
    Reserver
</a>
		</div>

		<!-- Rangée de contenu -->


		<!-- Tables des rendez-vous -->
        <div class="card shadow mb-4">
            <div class="card-header tab" style="padding: 0px !important;background: #2e2e2e!important">
            	<button class="tablinks active" onclick="openTab(event, 'Upcoming')">
            		Réservations à venir
            	</button>
                <button class="tablinks" onclick="openTab(event, 'All')">
                	Toutes les réservations
                </button>
                <button class="tablinks" onclick="openTab(event, 'Canceled')">
                	Réservations annulées
                </button>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
                	<table class="table table-bordered tabcontent" id="Upcoming" style="display:table" width="100%" cellspacing="0">
                  		<thead>
                                <tr>
                                    <th>
                                        Heure de début
                                    </th>
                                    <th>
                                        Services réservés
                                    </th>
                                    <th>
                                        Heure de fin prévue
                                    </th>
                                    <th>
                                        Client
                                    </th>
                                    <th>
                                        Employé
                                    </th>
                                    <th>
                                        Gérer
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    $stmt = $con->prepare("SELECT * 
                                                    FROM appointments a , clients c
                                                    WHERE start_time >= ?
                                                    AND a.client_id = c.client_id
                                                    AND a.client_id = ?
                                                    AND canceled = 0
                                                    ORDER BY start_time;
                                                    ");
                                    $stmt->execute(array(date('Y-m-d H:i:s'), $client_id));
                                    $rows = $stmt->fetchAll();
                                    $count = $stmt->rowCount();
                                    
                                    

                                    if($count == 0)
                                    {

                                        echo "<tr>";
                                            echo "<td colspan='5' style='text-align:center;'>";
                                                echo "La liste de vos réservations à venir sera présentée ici";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    else
                                    {

                                        foreach($rows as $row)
                                        {
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo $row['start_time'];
                                                echo "</td>";
                                                echo "<td>";
                                                    $stmtServices = $con->prepare("SELECT service_name
                                                            from services s, services_booked sb
                                                            where s.service_id = sb.service_id
                                                            and appointment_id = ?");
                                                    $stmtServices->execute(array($row['appointment_id']));
                                                    $rowsServices = $stmtServices->fetchAll();
                                                    foreach($rowsServices as $rowsService)
                                                    {
                                                        echo "- ".$rowsService['service_name'];
                                                        if (next($rowsServices)==true)  echo " <br> ";
                                                    }
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $row['end_time_expected'];
                                            
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<a href = #>";
                                                        echo $row['client_id'];
                                                    echo "</a>";
                                                echo "</td>";
                                                echo "<td>";
                                                    $stmtEmployees = $con->prepare("SELECT first_name,last_name
                                                            from employees e, appointments a
                                                            where e.employee_id = a.employee_id
                                                            and a.appointment_id = ?");
                                                    $stmtEmployees->execute(array($row['appointment_id']));
                                                    $rowsEmployees = $stmtEmployees->fetchAll();
                                                    foreach($rowsEmployees as $rowsEmployee)
                                                    {
                                                        echo $rowsEmployee['first_name']." ".$rowsEmployee['last_name'];
                                                        
                                                    }
                                                echo "</td>";
                                                
                                                echo "<td>";
                                                	$cancel_data = "cancel_appointment_".$row["appointment_id"];
                                               		?>
                                               		<ul class="list-inline m-0">

                                                        <!-- BOUTON ANNULER -->

                                                        <li class="list-inline-item" data-toggle="tooltip" title="Annuler le rendez-vous">
                                                            <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $cancel_data; ?>" data-placement="top">
                                                                <i class="fas fa-calendar-times"></i>
                                                            </button>

                                                            <!-- MODAL ANNULER -->
                                                      <!-- MODAL ANNULER -->
                                                      <div class="modal fade" id="cancel_appointment_<?php echo $row['appointment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="cancel_appointment_<?php echo $row['appointment_id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Annuler le rendez-vous</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
<!-- Other HTML code above -->
<div class="modal-body">
    <p>Voulez-vous vraiment annuler ce rendez-vous?</p>
    <form action="cancel_appointment.php" method="post">
    <label>Pourquoi annulez-vous?</label>
    <input type="text" name="cancellation_reason">
        <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button> <!-- This button should not be inside the form if it's not submitting anything -->
        <button type="submit" class="btn btn-danger">Oui, annuler</button>
    </form>
</div>
    </div>
</div>

                                                        </li>
                                                    </ul>

                                               		<?php
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                    }

                                ?>

                            </tbody>
                	</table>
                	<table class="table table-bordered tabcontent" id="All" width="100%" cellspacing="0">
                  		<thead>
                            <tr>
                                <th>
                                    Heure de début
                                </th>
                                <th>
                                    Services réservés
                                </th>
                                <th>
                                    Heure de fin prévue
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Employé
                                </th>
                                <th>
                                Statut
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                $stmt = $con->prepare("SELECT * 
                                                FROM appointments a , clients c
                                                WHERE a.client_id = c.client_id
                                                AND a.client_id = ?
                                                ORDER BY start_time;
                                                ");
                                $stmt->execute(array($client_id));
                                $rows = $stmt->fetchAll();
                                $count = $stmt->rowCount();

                                if($count == 0)
                                {

                                    echo "<tr>";
                                        echo "<td colspan='5' style='text-align:center;'>";
                                            echo "La liste de toutes vos réservations sera présentée ici";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                else
                                {

                                    foreach($rows as $row)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $row['start_time'];
                                            echo "</td>";
                                            echo "<td>";
                                                $stmtServices = $con->prepare("SELECT service_name
                                                        from services s, services_booked sb
                                                        where s.service_id = sb.service_id
                                                        and appointment_id = ?");
                                                $stmtServices->execute(array($row['appointment_id']));
                                                $rowsServices = $stmtServices->fetchAll();
                                                foreach($rowsServices as $rowsService)
                                                {
                                                    echo $rowsService['service_name'];
                                                    if (next($rowsServices)==true)  echo " + ";
                                                }
                                            echo "</td>";
                                            echo "<td>";
                                                echo $row['end_time_expected'];
                                        
                                            echo "</td>";
                                            echo "<td>";
                                            echo $row['first_name']." ".$row['last_name'];
                                            echo "</td>";
                                            echo "<td>";
                                                $stmtEmployees = $con->prepare("SELECT first_name,last_name
                                                        from employees e, appointments a
                                                        where e.employee_id = a.employee_id
                                                        and a.appointment_id = ?");
                                                $stmtEmployees->execute(array($row['appointment_id']));
                                                $rowsEmployees = $stmtEmployees->fetchAll();
                                                foreach($rowsEmployees as $rowsEmployee)
                                                {
                                                    echo $rowsEmployee['first_name']." ".$rowsEmployee['last_name'];
                                                    
                                                }
                                            echo "</td>";
                                            echo "<td>";
                                            $stmtet = $con->prepare("SELECT status FROM appointments WHERE employee_id = ? AND appointment_id = ?");
                                            $stmtet->execute(array($row['employee_id'], $row['appointment_id']));
                                            $statuses = $stmtet->fetchAll(PDO::FETCH_COLUMN, 0);
                                            
                                            $translations = [
                                                'canceled' => 'annulé',
                                                'ongoing' => 'en cours'
                                            ];
                                            
                                            foreach ($statuses as $status) {
                                                echo $translations[$status] ?? $status; // Translate or keep the status as is
                                            }
                                            echo "</td>";
                                            
                                            
                                         
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                	</table>
                	<table class="table table-bordered tabcontent" id="Canceled" width="100%" cellspacing="0">
                  		<thead>
                            <tr>
                                <th>
                                Date debut
                                </th>
                                <th>
                                Services réservés
                                </th>
                                <th>
                                Client
                                </th>
                                <th>
                                Raison d'annulation
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                $stmt = $con->prepare("SELECT * 
                                                FROM appointments a , clients c
                                                WHERE canceled = 1
                                                AND a.client_id = c.client_id
                                                AND a.client_id = ?
                                                ");
                                $stmt->execute(array($client_id));
                                $rows = $stmt->fetchAll();
                                $count = $stmt->rowCount();

                                if($count == 0)
                                {

                                    echo "<tr>";
                                        echo "<td colspan='5' style='text-align:center;'>";
                                            echo "La liste de tous les reservation annules";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                else
                                {

                                    foreach($rows as $row)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $row['start_time'];
                                            echo "</td>";

                                            echo "<td>";
                                            $stmtServices = $con->prepare("SELECT service_name
                                                    from services s, services_booked sb
                                                    where s.service_id = sb.service_id
                                                    and appointment_id = ?");
                                            $stmtServices->execute(array($row['appointment_id']));
                                            $rowsServices = $stmtServices->fetchAll();
                                            foreach($rowsServices as $rowsService)
                                            {
                                                echo $rowsService['service_name'];
                                                if (next($rowsServices)==true)  echo " + ";
                                            }
                                        echo "</td>";
                                            echo "<td>";
                                            echo $row['first_name']." ".$row['last_name'];
                                            echo "</td>";
                                            echo "<td>";
                                                
                                                echo $row['cancellation_reason'];
                                                    
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                }

                            ?>

                        </tbody>
                	</table>
              	</div>
            </div>
        </div>
	</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>
</html>

<?php
    // Include Footer
    include 'templates/footer.php';
} else {
    header('Location: login.php');
    exit();
}
?>
