<?php 
	session_start();

	//Vérifier si l'utilisateur est déjà connecté
	if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
	{
        //Titre de la page
        $titreDePage = 'Tableau de bord';

        //Inclusions
        include 'connect.php';
        include 'Includes/functions/functions.php'; 
        include 'Includes/templates/header.php';

?>
	<!-- Commencer le contenu de la page -->
	<div class="container-fluid">
		
		<!-- Titre de la page -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
		
		</div>

		<!-- Rangée de contenu -->
		<div class="row">

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
				  		<div class="row no-gutters align-items-center">
							<div class="col mr-2">
					  			<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
					  				Total des clients
					  			</div>
					  			<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("client_id","clients")?></div>
							</div>
							<div class="col-auto">
					  			<i class="bs bs-boy fa-2x text-gray-300"></i>
							</div>
				  		</div>
					</div>
			  	</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-success shadow h-100 py-2">
					<div class="card-body">
				  		<div class="row no-gutters align-items-center">
							<div class="col mr-2">
					  			<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
					  				Total des services
					  			</div>
					  			<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("service_id","services")?></div>
							</div>
							<div class="col-auto">
					  			<i class="bs bs-scissors-1 fa-2x text-gray-300"></i>
							</div>
				  		</div>
					</div>
			  	</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-info shadow h-100 py-2">
					<div class="card-body">
				  		<div class="row no-gutters align-items-center">
							<div class="col mr-2">
					  			<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
					  				Employés
					  			</div>
					  			<div class="row no-gutters align-items-center">
									<div class="col-auto">
						  				<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo countItems("employee_id","employees")?></div>
									</div>
					  			</div>
							</div>
							<div class="col-auto">
					  			<i class="bs bs-man fa-2x text-gray-300"></i>
							</div>
				  		</div>
					</div>
			  	</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-warning shadow h-100 py-2">
					<div class="card-body">
				  		<div class="row no-gutters align-items-center">
							<div class="col mr-2">
					  			<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
					  				Rendez-vous
					  			</div>
					  			<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("appointment_id","appointments")?></div>
							</div>
							<div class="col-auto">
					  			<i class="fas fa-calendar fa-2x text-gray-300"></i>
							</div>
				 		</div>
					</div>
			  	</div>
			</div>
		</div>

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
                                                    where start_time >= ?
                                                    and a.client_id = c.client_id
                                                    and  status = 'ongoing' and canceled = 0 
                                                    order by start_time;
                                                    ");
                                    $stmt->execute(array(date('Y-m-d H:i:s')));
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
                                                    $success_data = "success_appointment_".$row["appointment_id"];

                                               		?>
                                               		<ul class="list-inline m-0">

                                                        <!-- BOUTON ANNULER -->

                                                        <li class="list-inline-item" data-toggle="tooltip" title="Annuler le rendez-vous">
                                                            <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $cancel_data; ?>" data-placement="top">
                                                                <i class="fas fa-calendar-times"></i>
                                                            </button>

                                                            <!-- MODAL ANNULER -->
                                                            <div class="modal fade" id="<?php echo $cancel_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $cancel_data; ?>" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Annuler le rendez-vous</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Voulez-vous vraiment annuler ce rendez-vous?</p>
                                                                            <div class="form-group">
                                                                                <label>Pourquoi annulez-vous?</label>
                                                                                <textarea class="form-control" id=<?php echo "appointment_cancellation_reason_".$row['appointment_id'] ?>></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                                                            <button type="button" data-id = "<?php echo $row['appointment_id']; ?>" class="btn btn-danger cancel_appointment_button">Oui, annuler</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <li class="list-inline-item" data-toggle="tooltip" title="Etat Passe">
                                                            <form action="success_appointment.php" method="post">
    <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
    <button class="btn btn-success btn-sm rounded-0" type="submit">
        <i class="fas fa-check"></i>
    </button>
</form>
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
                                    Service réservé
                                </th>
                                <th>
                                    Heure de fin prévue
                                </th><th>
                                    Statut
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Employé
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                $stmt = $con->prepare("SELECT * 
                                                FROM appointments a , clients c
                                                where a.client_id = c.client_id
                                                order by start_time;
                                                ");
                                $stmt->execute(array());
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
                                            echo $row['status'];
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
                                Date Debut
                                </th><th>
                                Service
                                </th>
                                <th>
                                Client
                                </th>
                                <th>
                                Employé
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
                                                where canceled = 1
                                                and a.client_id = c.client_id
                                                ");
                                $stmt->execute(array());
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


<?php
        
		//Include Footer
		include 'Includes/templates/footer.php';
	}
	else
    {
    	header('Location: login.php');
        exit();
    }

?>
