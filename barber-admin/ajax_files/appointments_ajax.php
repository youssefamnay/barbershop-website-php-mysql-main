<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>


<?php
	

	if(isset($_POST['do']) && $_POST['do'] == "Cancel Appointment")
	{
		$appointment_id = $_POST['appointment_id'];
		$cancellation_reason =  test_input($_POST['cancellation_reason']);

		$stmt = $con->prepare("UPDATE appointments SET status = 'canceled', canceled = 1, cancellation_reason = ? WHERE appointment_id = ?");
        $stmt->execute(array($cancellation_reason, $appointment_id));    
	}
	
?>