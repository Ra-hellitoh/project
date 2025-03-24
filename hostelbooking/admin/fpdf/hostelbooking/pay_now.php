<?php 
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');

  date_default_timezone_set("Asia/Kolkata");

  session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }

  if(isset($_POST['pay_now']))
  {
    header("Pragma: no-cache");
    header("Cache-Control: no-cache");
    header("Expires: 0");

    $ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111,9999999);    
    $CUST_ID = $_SESSION['uId'];

    // Insert booking data into database

    $frm_data = filteration($_POST);

    $query1 = "INSERT INTO booking_order(user_id, room_id, booking_status, check_in, check_out, order_id, trans_amt, trans_status) 
           VALUES (?,?,?,?,?,?,?,?)";

    insert($query1, [$CUST_ID, $_SESSION['room']['id'], 'booked', $frm_data['checkin'], 
          $frm_data['checkout'], $ORDER_ID, '9000', 'TXN_SUCCESS'], 'isssssss');

    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`,
      `user_name`, `phonenum`, `pincode`) VALUES (?,?,?,?,?,?,?)";

    insert($query2,[$booking_id,$_SESSION['room']['name'],$_SESSION['room']['price'],
      '9000',$frm_data['name'],$frm_data['phonenum'],$frm_data['pincode']],'issssss');
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
