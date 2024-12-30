<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

$doctor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "DELETE FROM doctors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();


header("Location: /project_wad/frontend/admin/doctors/manage_doctors.php");
exit;
?>
