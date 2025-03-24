<?php
require('inc/db_config.php');
require('tcpdf/tcpdf.php'); // Ensure TCPDF is in your project directory

// Extend TCPDF to add a custom header
class MYPDF extends TCPDF {
    // Page Header
    public function Header() {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'New Bookings Report', 0, 1, 'C');
        $this->Ln(5);
    }
}

// Create PDF document
$pdf = new MYPDF();
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Fetch booking data
$con = mysqli_connect("localhost", "root", "", "your_database_name"); // Update your DB details
$query = "SELECT bo.*, u.name AS user_name, u.email, r.name AS room_name, r.price 
          FROM booking_order bo
          JOIN users u ON bo.user_id = u.id
          JOIN rooms r ON bo.room_id = r.id
          WHERE bo.booking_status = 'booked'";
$result = mysqli_query($con, $query);

// Create table in HTML
$html = '<table border="1" cellpadding="5">
            <tr style="background-color:#f2f2f2;">
                <th>#</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Room Name</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>';

$counter = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
                <td>' . $counter++ . '</td>
                <td>' . htmlspecialchars($row['user_name']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['room_name']) . '</td>
                <td>' . $row['check_in'] . '</td>
                <td>' . $row['check_out'] . '</td>
                <td>' . number_format($row['trans_amt'], 2) . '</td>
                <td>' . ucfirst($row['booking_status']) . '</td>
              </tr>';
}

$html .= '</table>';

// Add content to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF file
$pdf->Output('New_Bookings_Report.pdf', 'D'); // Force download

?>
