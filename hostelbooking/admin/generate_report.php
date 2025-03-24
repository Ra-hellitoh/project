<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('inc/db_config.php');
require('fpdf/fpdf.php'); 

// Extend FPDF to add a custom header and footer
class PDF extends FPDF {
    // Page Header
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 10, 'New Bookings Report', 0, 1, 'C');
        $this->Ln(5);
    }

    // Page Footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Create a new PDF instance
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Database connection
$con = mysqli_connect("localhost", "root", "4rever2moro", "hotelbooking"); 
$query = "SELECT bo.*, u.name AS user_name, u.email, r.name AS room_name, r.price 
          FROM booking_order bo
          JOIN user_cred u ON bo.user_id = u.id
          JOIN rooms r ON bo.room_id = r.id
          WHERE bo.booking_status = 'booked'";
$result = mysqli_query($con, $query);

// Table headers
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, '#', 1);
$pdf->Cell(40, 10, 'User Name', 1);
$pdf->Cell(50, 10, 'Email', 1);
$pdf->Cell(30, 10, 'Room', 1);
$pdf->Cell(20, 10, 'Check-In', 1);
$pdf->Cell(20, 10, 'Check-Out', 1);
$pdf->Cell(20, 10, 'Amount', 1);
$pdf->Ln();

// Fetch and display data
$pdf->SetFont('Arial', '', 10);
$counter = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(10, 10, $counter++, 1);
    $pdf->Cell(40, 10, htmlspecialchars($row['user_name']), 1);
    $pdf->Cell(50, 10, htmlspecialchars($row['email']), 1);
    $pdf->Cell(30, 10, htmlspecialchars($row['room_name']), 1);
    $pdf->Cell(20, 10, $row['check_in'], 1);
    $pdf->Cell(20, 10, $row['check_out'], 1);
    $pdf->Cell(20, 10, number_format($row['price'], 2), 1);
    $pdf->Ln();
}

// Output the PDF
$pdf->Output('New_Bookings_Report.pdf', 'D'); // Force download
?>
