<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

session_start();
date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['book_room'])) {
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "User not logged in."]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if (empty($check_in) || empty($check_out)) {
        echo json_encode(["status" => "error", "message" => "Check-in and check-out dates are required."]);
        exit;
    }

    // Validate dates
    $today = new DateTime(date("Y-m-d"));
    $checkin_date = new DateTime($check_in);
    $checkout_date = new DateTime($check_out);

    if ($checkin_date == $checkout_date || $checkout_date < $checkin_date || $checkin_date < $today) {
        echo json_encode(["status" => "error", "message" => "Invalid check-in/check-out dates."]);
        exit;
    }

    // Check if room is available
    $tb_query = "SELECT COUNT(*) AS total_bookings FROM booking_order
                 WHERE booking_status=? AND room_id=?
                 AND check_out > ? AND check_in < ?";
    $values = ['booked', $room_id, $check_in, $check_out];
    $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));

    $rq_result = select("SELECT quantity FROM rooms WHERE id=?", [$room_id], 'i');
    $rq_fetch = mysqli_fetch_assoc($rq_result);

    if (($rq_fetch['quantity'] - $tb_fetch['total_bookings']) == 0) {
        echo json_encode(["status" => "error", "message" => "Room is not available."]);
        exit;
    }

    // Insert booking
    $insert_query = "INSERT INTO booking_order (user_id, room_id, check_in, check_out, booking_status)
                     VALUES (?, ?, ?, ?, ?)";
    $values = [$user_id, $room_id, $check_in, $check_out, 'booked'];

    if (insert($insert_query, $values, 'iisss')) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error, try again."]);
    }
}
?>
