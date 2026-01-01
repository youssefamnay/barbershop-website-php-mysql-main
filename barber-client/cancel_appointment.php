<?php
session_start();
include 'connect.php';

if (isset($_POST['appointment_id']) && isset($_POST['cancellation_reason'])) {
    $appointmentId = $_POST['appointment_id'];
    $cancellation_reason = $_POST['cancellation_reason'];
    $stmt = $con->prepare("UPDATE appointments SET status = 'canceled', canceled = 1, cancellation_reason = ? WHERE appointment_id = ?");
    $stmt->execute([$cancellation_reason, $appointmentId]);

    header("Location: index.php");
    exit(); 
} else { 
    header("Location: index.php");
    exit(); // Ensure to exit after redirecting
}
?>