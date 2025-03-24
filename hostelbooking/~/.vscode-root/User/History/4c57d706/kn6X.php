<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('admin/inc/essentials.php');
require('admin/inc/db_config.php');
require('admin/inc/mpdf/vendor/autoload.php');

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

if (isset($_GET['gen_pdf']) && isset($_GET['id'])) {
    $frm_data = filteration($_GET);

    $id = $_GET['id'] ?? '';

    $query = "SELECT bo.*, bd.*, uc.email FROM booking_order bo
              INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
              INNER JOIN user_cred uc ON bo.user_id = uc.id
              WHERE ((bo.booking_status='booked') 
              OR (bo.booking_status='cancelled')
              OR (bo.booking_status='payment failed')) 
              AND bo.booking_id = '$id'";


    $res = mysqli_query($con, $query);
    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        die("No booking data found.");
    }

    $data = mysqli_fetch_assoc($res);
    if (!$data) {
        die("Error: Missing booking details.");
    }

    // Convert date safely
    $date = !empty($data['datentime']) ? date("h:ia | d-m-Y", strtotime($data['datentime'])) : "N/A";
    $checkin = !empty($data['check_in']) ? date("d-m-Y", strtotime($data['check_in'])) : "N/A";
    $checkout = !empty($data['check_out']) ? date("d-m-Y", strtotime($data['check_out'])) : "N/A";

    $table_data = "
    <h2>BOOKING RECEIPT</h2>
    <table border='1'>
      <tr>
        <td>Order ID: " . ($data['order_id'] ?? "N/A") . "</td>
        <td>Booking Date: $date</td>
      </tr>
      <tr>
        <td colspan='2'>Status: " . ($data['booking_status'] ?? "N/A") . "</td>
      </tr>
      <tr>
        <td>Name: " . ($data['user_name'] ?? "N/A") . "</td>
        <td>Email: " . ($data['email'] ?? "N/A") . "</td>
      </tr>
      <tr>
        <td>Phone Number: " . ($data['phonenum'] ?? "N/A") . "</td>
        <td>Address: " . ($data['address'] ?? "N/A") . "</td>
      </tr>
      <tr>
        <td>Room Name: " . ($data['room_name'] ?? "N/A") . "</td>
        <td>Cost: KES " . ($data['price'] ?? "0") . " per semester</td>
      </tr>
      <tr>
        <td>Check-in: $checkin</td>
        <td>Check-out: $checkout</td>
      </tr>
    ";

    if ($data['booking_status'] == 'cancelled') {
        $refund = (!empty($data['refund']) && $data['refund'] == 1) ? "Amount Refunded" : "Not Yet Refunded";
        $table_data .= "<tr>
            <td>Amount Paid: KES " . ($data['trans_amt'] ?? "0") . "</td>
            <td>Refund: $refund</td>
        </tr>";
    } elseif ($data['booking_status'] == 'payment failed') {
        $table_data .= "<tr>
            <td>Transaction Amount: KES " . ($data['trans_amt'] ?? "0") . "</td>
            <td>Failure Response: " . ($data['trans_resp_msg'] ?? "N/A") . "</td>
        </tr>";
    } else {
        $table_data .= "<tr>
            <td>Room Number: " . ($data['room_no'] ?? "N/A") . "</td>
            <td>Amount Paid: KES " . ($data['trans_amt'] ?? "0") . "</td>
        </tr>";
    }

    $table_data .= "</table>";

    // Ensure mPDF temporary directory is writable
    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => __DIR__ . '/admin/inc/mpdf/tmp'
    ]);

    $mpdf->WriteHTML($table_data);
    $mpdf->Output(($data['order_id'] ?? "receipt") . '.pdf', 'I');
} else {
    echo 'failed';
}
