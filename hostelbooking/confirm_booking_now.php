<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");
session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    die("Unauthorized access.");
}

$frm_data = filteration($_POST);
$room_number = $frm_data['room_number'] ?? null;
$checkin_date = $frm_data['checkin'] ?? null;
$checkout_date = $frm_data['checkout'] ?? null;

if (!$room_number || !$checkin_date || !$checkout_date) {
    die("Room number, check-in date, and check-out date are required.");
}

// Fetch the latest booking for the given room number
$fetch_booking_query = "SELECT booking_id FROM booking_order WHERE room_id = ? AND booking_status = 'booked' ORDER BY booking_id DESC LIMIT 1";
$stmt = $con->prepare($fetch_booking_query);
$stmt->bind_param("s", $room_number);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows === 0) {
    die("No active booking found for this room.");
}

$booking = $result->fetch_assoc();
$booking_id = $booking['booking_id'];

// Update check-in and check-out dates
$update_booking_query = "UPDATE booking_order SET check_in = ?, check_out = ? WHERE booking_id = ?";
$stmt = $con->prepare($update_booking_query);
$stmt->bind_param("ssi", $checkin_date, $checkout_date, $booking_id);

if ($stmt->execute()) {
    echo "Check-in successful.";
    header("Location: bookings.php");
    exit();
} else {
    die("Failed to update check-in.");
}

$stmt->close();
$con->close();
?>
