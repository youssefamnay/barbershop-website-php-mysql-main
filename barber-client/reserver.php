<!-- PHP INCLUDES -->

<?php

    include "connect.php";
	session_start();

	if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
	

 

    include "functions/functions.php";
	include 'templates/header.php';
   // Retrieve user information from the database
   $stmt = $con->prepare("SELECT * FROM clients WHERE client_id = ?");
   $stmt->execute(array($_SESSION['user_id']));
   $user = $stmt->fetch();
?>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
<!-- Appointment Page Stylesheet -->
<style>
	body
{
    background: #f7f7f7;
}

.text_header
{
    margin-bottom: 5px;
    font-size: 18px;
    font-weight: bold;
    line-height: 1.5;
    margin-top: 22px;
}

.booking_section
{
    max-width: 720px;
    margin: 50px auto;
    /*min-height: 500px;*/
}

.items_tab
{
    border-radius: 4px;
    background-color: white;
    overflow: hidden;
    box-shadow: 0 0 5px 0 rgba(60, 66, 87, 0.04), 0 0 10px 0 rgba(0, 0, 0, 0.04);
}

.itemListElement
{
    font-size: 14px;
    line-height: 1.29;
    border-bottom: solid 1px #e5e5e5;
    cursor: pointer;
    padding: 16px 12px 18px 12px;
}

.item_details
{
    width: auto;
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -webkit-flex-direction: row;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -webkit-box-align: center;
    -webkit-align-items: center;
}

.servide_name
{
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
    padding-right: 15px;
    color: #979797;
    -webkit-flex: 1;
}

.item_label
{
    color: #9e8a78;
    border-color: #9e8a78;
    background: white;
    font-size: 12px;
    font-weight: 700;
}

.btn-secondary:not(:disabled):not(.disabled).active, .btn-secondary:not(:disabled):not(.disabled):active 
{
    color: #fff;
    background-color: #9e8a78;
    border-color: #9e8a78;
}

.item_select_part
{
    display: flex;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
    flex-shrink: 0;
}

.select_item_bttn
{
    width: 55px;
    display: flex;
    margin-left: 30px;
    -webkit-box-pack: end;
    justify-content: flex-end;
}

.service_duration_field
{
    text-align: right;
    min-width: 60px;
    width: auto;
    color: rgb(151, 151, 151);
    line-height: 1.29;
    font-size: 14px;
}

.service_price_field
{
    width: auto;
    display: flex;
    margin-left: 30px;
    -webkit-box-align: baseline;
    align-items: baseline;
}

.radio_employee_select
{
    position: absolute;
    clip: rect(0,0,0,0);
    pointer-events: none;
}

/* Make circles that indicate the steps of the form: */
.step 
{
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;  
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
}

.step.active 
{
    opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish 
{
    background-color: #856b0d;
}

.next_prev_buttons
{
    background-color: #856b0d;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 17px;
    cursor: pointer;
}

.tab_reservation
{
    display: none;
}

.client_details_div .form-control
{
    background-color: #fff;
    border-radius: 0;
    padding: 25px 10px;
    box-shadow: none;
    border: 2px solid #eee;
    margin: 10px 0px;
}

.client_details_div .form-control:focus 
{
    border-color: #9e8a78;
    box-shadow: none;
    outline: none;
}
</style>

<!-- BOOKING APPOINTMENT SECTION -->

<section class="booking_section">
	<div class="container">

    <?php

if(isset($_POST['submit_book_appointment_form']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Selected SERVICES
    $selected_services = $_POST['selected_services'];
    
    // Selected EMPLOYEE
    $selected_employee = $_POST['selected_employee'];
    
    // Selected DATE+TIME
    $selected_date_time = explode(' ', $_POST['desired_date_time']);
    $date_selected = $selected_date_time[0];
    $start_time = $date_selected." ".$selected_date_time[1];
    $end_time = $date_selected." ".$selected_date_time[2];
    
    // Client Details
    $client_first_name = test_input($_POST['client_first_name']);
    $client_last_name = test_input($_POST['client_last_name']);
    $client_phone_number = test_input($_POST['client_phone_number']);
    $client_email = test_input($_POST['client_email']);
    
    $con->beginTransaction();
    
    try {
        // Check If the client's email already exists in our database
        $stmtCheckClient = $con->prepare("SELECT * FROM clients WHERE client_email = ?");
        $stmtCheckClient->execute(array($client_email));
        $client_result = $stmtCheckClient->fetch();
        $client_count = $stmtCheckClient->rowCount();
        
        if($client_count > 0) {
            $client_id = $client_result["client_id"];
        } else {
            $stmtClient = $con->prepare("INSERT INTO clients(first_name, last_name, phone_number, client_email) VALUES(?, ?, ?, ?)");
            $stmtClient->execute(array($client_first_name, $client_last_name, $client_phone_number, $client_email));
            $client_id = $con->lastInsertId();
        }
        
        // Insert appointment and get the new appointment_id
        $stmt_appointment = $con->prepare("INSERT INTO appointments(date_created, client_id, employee_id, start_time, end_time_expected) VALUES(?, ?, ?, ?, ?)");
        $stmt_appointment->execute(array(Date("Y-m-d H:i"), $client_id, $selected_employee, $start_time, $end_time));
        $appointment_id = $con->lastInsertId();
        
        // Insert services booked
        foreach($selected_services as $service) {
            $stmt = $con->prepare("INSERT INTO services_booked(appointment_id, service_id) VALUES(?, ?)");
            $stmt->execute(array($appointment_id, $service));
        }
        
        echo "<div class='alert alert-success'>";
        echo "Super! Votre rendez-vous a été créé avec succès.";
        echo "</div>";
        
        $con->commit();
    } catch(Exception $e) {
        $con->rollBack();
        echo "<div class='alert alert-danger'>";
        echo $e->getMessage();
        echo "</div>";
    }
}
?>



		<!-- RESERVATION FORM -->

		<form method="post" id="appointment_form" action="reserver.php">
		
			<!-- SELECT SERVICE -->

			<div class="select_services_div tab_reservation" id="services_tab">

				<!-- ALERT MESSAGE -->

				<div class="alert alert-danger" role="alert" style="display: none">
				S'il vous plaît, sélectionnez au moins un service !
			</div>

				<div class="text_header">
					<span>
						1. Choix des services
					</span>
				</div>

				<!-- SERVICES TAB -->
				
				<div class="items_tab">
        			<?php
        				$stmt = $con->prepare("Select * from services");
                    	$stmt->execute();
                    	$rows = $stmt->fetchAll();

                    	foreach($rows as $row)
                    	{
                        	echo "<div class='itemListElement'>";
                            	echo "<div class = 'item_details'>";
                                	echo "<div>";
                                    	echo $row['service_name'];
                                	echo "</div>";
                                	echo "<div class = 'item_select_part'>";
                                		echo "<span class = 'service_duration_field'>";
                                    		echo $row['service_duration']." min";
                                    	echo "</span>";
                                    	echo "<div class = 'service_price_field'>";
    										echo "<span style = 'font-weight: bold;'>";
                                    			echo $row['service_price']."DH";
                                    		echo "</span>";
                                    	echo "</div>";
                                    ?>
                                    	<div class="select_item_bttn">
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="service_label item_label btn btn-secondary">
            <input type="checkbox" name="selected_services[]" value="<?php echo $row['service_id'] ?>" autocomplete="off">✓
        </label>
    </div>
</div>

                                    <?php
                                	echo "</div>";
                            	echo "</div>";
                        	echo "</div>";
                    	}
            		?>
    			</div>
			</div>

			<!-- SELECT EMPLOYEE -->

			<div class="select_employee_div tab_reservation" id="employees_tab">

				<!-- ALERT MESSAGE -->

				<div class="alert alert-danger" role="alert" style="display: none">
					S'il vous plait choisissez votre employée
				</div>

				<div class="text_header">
					<span>
						2.Choisissez votre employée.
					</span>
				</div>

				<!-- EMPLOYEES TAB -->
				
				<div class="btn-group-toggle" data-toggle="buttons">
					<div class="items_tab">
        				<?php
        					$stmt = $con->prepare("Select * from employees");
                    		$stmt->execute();
                    		$rows = $stmt->fetchAll();

                    		foreach($rows as $row)
                    		{
                        		echo "<div class='itemListElement'>";
                            		echo "<div class = 'item_details'>";
                                		echo "<div>";
                                    		echo $row['first_name']." ".$row['last_name'];
                                		echo "</div>";
                                		echo "<div class = 'item_select_part'>";
                                    ?>
                                    		<div class="select_item_bttn">
                                    			<label class="item_label btn btn-secondary active">
													<input type="radio" class="radio_employee_select" name="selected_employee" value="<?php echo $row['employee_id'] ?>">Select
												</label>	
                                    		</div>
                                    <?php
                                		echo "</div>";
                            		echo "</div>";
                        		echo "</div>";
                    		}
            			?>
    				</div>
    			</div>
			</div>


			<!-- SELECT DATE TIME -->

			<div class="select_date_time_div tab_reservation" id="calendar_tab">

				<!-- ALERT MESSAGE -->
				
		        <div class="alert alert-danger" role="alert" style="display: none">
		        S'il vous plaît, sélectionnez l'heure !
		        </div>

				<div class="text_header">
					<span>
						3. Choix de la date et de l'heure
					</span>
				</div>
				
				<div class="calendar_tab" style="overflow-x: auto;overflow-y: visible;" id="calendar_tab_in">
					<div id="calendar_loading">
						<img src="Design/images/ajax_loader_gif.gif" style="display: block;margin-left: auto;margin-right: auto;">
					</div>
				</div>

			</div>


			<!-- CLIENT DETAILS -->

			<div class="client_details_div tab_reservation" id="client_tab">

                <div class="text_header">
                    <span>
                        4. Détails du client
                    </span>
                </div>

                <div>
                    <div class="form-group colum-row row">
                  <div class="col-sm-6">
                <input type="text" name="client_first_name" id="client_first_name" class="form-control" placeholder="Nom" value="<?php echo $user['first_name']; ?>" readonly>
                <span class = "invalid-feedback">Ce champ est obligatoire</span>
            </div>
            <div class="col-sm-6">
                <input type="text" name="client_last_name" id="client_last_name" class="form-control" placeholder="Prenom" value="<?php echo $user['last_name']; ?>" readonly>
                <span class = "invalid-feedback">Ce champ est obligatoire</span>
            </div>
            <div class="col-sm-6">
                <input type="email" name="client_email" id="client_email" class="form-control" placeholder="E-mail" value="<?php echo $user['client_email']; ?>" readonly>
                <span class = "invalid-feedback"> E-mail Invalide</span>
            </div>
            <div class="col-sm-6">
                <input type="text"  name="client_phone_number" id="client_phone_number" class="form-control" placeholder="Numero Telephone" value="<?php echo $user['phone_number']; ?>" readonly>
                <span class = "invalid-feedback">Telephone invalid</span>
            </div>
                    </div>
        
                </div>
            </div>


			

			<!-- NEXT AND PREVIOUS BUTTONS -->

			<div style="overflow:auto;padding: 30px 0px;">
    			<div style="float:right;">
    				<input type="hidden" name="submit_book_appointment_form">
      				<button type="button" id="prevBtn"  class="next_prev_buttons" style="background-color: #bbbbbb;"  onclick="nextPrev(-1)">Previous</button>
      				<button type="button" id="nextBtn" class="next_prev_buttons" onclick="nextPrev(1)">Next</button>
    			</div>
  			</div>

  			<!-- Circles which indicates the steps of the form: -->

  			<div style="text-align:center;margin-top:40px;">
    			<span class="step"></span>
    			<span class="step"></span>
    			<span class="step"></span>
    			<span class="step"></span>
  			</div>

		</form>
	</div>
</section>

<script>
	$(document).ready(function() {
    $('.service_label').click(function() {
        console.log('Checkbox clicked');
        $(this).button('toggle');
    });
});

</script>

<!-- FOOTER BOTTOM -->

<?php include "templates/footer.php"; ?>