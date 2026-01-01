<?php
session_start();
include 'connect.php';

if (isset($_POST['appointment_id'])) {
    $appointmentId = $_POST['appointment_id'];
    $stmt = $con->prepare("UPDATE appointments SET status = 'completed' WHERE appointment_id = ?");
    $stmt->execute([$appointmentId]);
    header("Location: index.php");
    exit();
} else {
    header("Location: error.php");
    exit(); // Ensure to exit after redirecting
}
?>