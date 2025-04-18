<?php 
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');

  date_default_timezone_set("Asia/Kolkata");

  session_start();

  if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
      redirect('index.php');
  }

  echo "Booking process started...";
  header("Pragma: no-cache");
  header("Cache-Control: no-cache");
  header("Expires: 0");

  $ORDER_ID = 'ORD_' . $_SESSION['uId'] . random_int(11111, 9999999);    
  $CUST_ID = $_SESSION['uId'];

  $frm_data = filteration($_POST);

  $room_id = $_SESSION['room']['id'] ?? null;
  $room_name = $_SESSION['room']['name'] ?? null;
  $room_price = $_SESSION['room']['price'] ?? null;     

  if (!$room_id || !$room_name || !$room_price) {
      die("Room details missing. Please try again.");
  }

  // Fetch user details
  $existing_booking_query = "SELECT COUNT(*) FROM booking_order WHERE user_id = ? AND booking_status = 'booked'";
  $stmt_check = $con->prepare($existing_booking_query);
  $stmt_check->bind_param("i", $CUST_ID);
  $stmt_check->execute();
  $stmt_check->bind_result($existing_booking_count);
  $stmt_check->fetch();
  $stmt_check->close();

  if ($existing_booking_count > 0) {
      die("You already have an active booking. Multiple room bookings are not allowed.");
  }

  if (!$user_name || !$user_phone || !$user_pincode) {
      die("User details missing. Please update your profile.");
  }

  // Start database transaction
  $con->begin_transaction();

  try {
      // Insert booking order
      $query1 = "INSERT INTO booking_order(user_id, room_id, booking_status, check_in, check_out, order_id, trans_amt, trans_status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

      $stmt1 = $con->prepare($query1);
      $stmt1->bind_param("iissssds", $CUST_ID, $room_id, $status, $frm_data['checkin'], 
                         $frm_data['checkout'], $ORDER_ID, $room_price, $trans_status);

      $status = 'booked';
      $trans_status = 'TXN_SUCCESS';

      if (!$stmt1->execute()) {
          throw new Exception("Error in booking order: " . $stmt1->error);
      }

      $booking_id = $con->insert_id;
      $stmt1->close();

      // Insert booking details with user info
      $query2 = "INSERT INTO booking_details (booking_id, room_name, price, total_pay, user_name, phonenum, pincode) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";

      $stmt2 = $con->prepare($query2);
      $stmt2->bind_param("isdssss", $booking_id, $room_name, $room_price, $room_price, 
                         $user_name, $user_phone, $user_pincode);

      if (!$stmt2->execute()) {
          throw new Exception("Error in booking details: " . $stmt2->error);
      }

      $stmt2->close();

      $con->commit();
      header("Location: bookings.php");
      exit();

  } catch (Exception $e) {
      // Rollback in case of an error
      $con->rollback();
      die("Booking failed: " . $e->getMessage());
  }
?>


<html>
  <head>
    <title>Processing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  </head>
  <body>
    <div class="container mt-5">
      <div class="col-12 px-4">
        <p class="fw-bold alert alert-success">
          <i class="bi bi-check-circle-fill"></i>
          Payment done! Booking successful.
          <br><br>
          <a href='bookings.php' class="btn btn-primary">Go to Bookings</a>
        </p>
      </div>
    </div>
  </body>
</html>
