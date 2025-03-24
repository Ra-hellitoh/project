<?php 
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");
session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    die(json_encode(["status" => "error", "message" => "Unauthorized access."]));
}

$frm_data = filteration($_POST);
$room_number = $frm_data['room_number'] ?? null;

if (!$room_number) {
    die(json_encode(["status" => "error", "message" => "Room number is required."]));
}

$checkin_date = date("Y-m-d H:i:s");

// Fetch the latest booking for the given room number
$fetch_booking_query = "SELECT booking_id, check_out FROM booking_order WHERE room_number = ? AND booking_status = 'booked' ORDER BY id DESC LIMIT 1";
$stmt = $con->prepare($fetch_booking_query);
$stmt->bind_param("s", $room_number);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows === 0) {
    die(json_encode(["status" => "error", "message" => "No active booking found for this room."]));
}

$booking = $result->fetch_assoc();
$booking_id = $booking['id'];
$checkout_date = $booking['check_out'] ?: date("Y-m-d H:i:s", strtotime("+1 day")); 

// Update check-in and check-out dates
$update_booking_query = "UPDATE booking_order SET check_in = ?, check_out = ? WHERE id = ?";
$stmt = $con->prepare($update_booking_query);
$stmt->bind_param("ssi", $checkin_date, $checkout_date, $booking_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Check-in successful", "checkin_date" => $checkin_date, "checkout_date" => $checkout_date]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update check-in."]);
}

$stmt->close();
$con->close();
?>
