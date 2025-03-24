<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

  require('inc/essentials.php');
  require('inc/db_config.php');
  require('inc/mpdf/vendor/autoload.php');

  adminLogin();

  if(isset($_GET['gen_pdf']) && isset($_GET['id']))
  {
    $frm_data = filteration($_GET);

    $query = "SELECT bo.*, bd.*,uc.email FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      INNER JOIN `user_cred` uc ON bo.user_id = uc.id
      WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
      OR (bo.booking_status='cancelled' AND bo.refund=1)
      OR (bo.booking_status='payment failed')) 
      AND bo.booking_id = '$frm_data[id]'";

    $res = mysqli_query($con,$query);
    $total_rows = mysqli_num_rows($res);

    if($total_rows==0){
      header('location: dashboard.php');
      exit;
    }

    $data = mysqli_fetch_assoc($res);

    $date = date("h:ia | d-m-Y",strtotime($data['datentime']));
    $checkin = date("d-m-Y",strtotime($data['check_in']));
    $checkout = date("d-m-Y",strtotime($data['check_out']));

    $logoPath = 'images/about/ku.png';

    $table_data = "
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        h2 { margin-top: 5px; }
        table { width: 80%; margin: auto; border-collapse: collapse; }
        td, th { border: 1px solid black; padding: 8px; text-align: left; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { max-width: 100px; }
    </style>

    <div class='header'>
        <img src='$logoPath' class='logo'>
        <h2>Kenyatta University Hostels</h2>
        <h3>BOOKING RECEIPT</h3>
    </div>

    <table>
      <tr>
        <td><strong>Order ID:</strong> " . ($data['order_id'] ?? "N/A") . "</td>
        <td><strong>Booking Date:</strong> $date</td>
      </tr>
      <tr>
        <td colspan='2'><strong>Status:</strong> " . ($data['booking_status'] ?? "N/A") . "</td>
      </tr>
      <tr>
        <td><strong>Name:</strong> " . ($data['user_name'] ?? "N/A") . "</td>
        <td><strong>Email:</strong> " . ($data['email'] ?? "N/A") . "</td>
      </tr>
      <tr>
        <td><strong>Phone Number:</strong> " . ($data['phonenum'] ?? "N/A") . "</td>
        <td><strong>Admission Number:</strong> " . ($data['pincode'] ?? "N/A") . "</td>
      </tr>
      <tr>
        <td><strong>Room Name:</strong> " . ($data['room_name'] ?? "N/A") . "</td>
        <td><strong>Cost:</strong> KES " . ($data['price'] ?? "0") . " per semester</td>
      </tr>
      <tr>
        <td><strong>Check-in:</strong> $checkin</td>
        <td><strong>Check-out:</strong> $checkout</td>
      </tr>";

    if ($data['booking_status'] == 'cancelled') {
        $refund = (!empty($data['refund']) && $data['refund'] == 1) ? "Amount Refunded" : "Not Yet Refunded";
        $table_data .= "<tr>
            <td><strong>Amount Paid:</strong> KES " . ($data['trans_amt'] ?? "0") . "</td>
            <td><strong>Refund:</strong> $refund</td>
        </tr>";
    } elseif ($data['booking_status'] == 'payment failed') {
        $table_data .= "<tr>
            <td><strong>Transaction Amount:</strong> KES " . ($data['trans_amt'] ?? "0") . "</td>
            <td><strong>Failure Response:</strong> " . ($data['trans_resp_msg'] ?? "N/A") . "</td>
        </tr>";
    } else {
        $table_data .= "<tr>
            <td><strong>Room Number:</strong> " . ($data['room_no'] ?? "N/A") . "</td>
            <td><strong>Amount Paid:</strong> KES " . ($data['trans_amt'] ?? "0") . "</td>
        </tr>";
    }

    $table_data .= "</table>";

    // Ensure mPDF temporary directory is writable
    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => __DIR__ . 'inc/mpdf/tmp'
    ]);
    $mpdf->WriteHTML($table_data);
    $mpdf->Output($data['order_id'].'.pdf','D');

  }
  else{
    header('location: dashboard.php');
  }
  
?>