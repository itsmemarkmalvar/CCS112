<?php
require('C:/xampp/htdocs/CCS112/fpdf/fpdf.php');
require_once 'db_connection.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,'User Activity Report',0,1,'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Fetch user activity data
$query = "SELECT Username, ActivityDate, Action FROM user_activity ORDER BY ActivityDate DESC LIMIT 100";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(50,10,'Username',1);
    $pdf->Cell(60,10,'Activity Date',1);
    $pdf->Cell(80,10,'Action',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',12);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(50,10,$row['Username'],1);
        $pdf->Cell(60,10,$row['ActivityDate'],1);
        $pdf->Cell(80,10,$row['Action'],1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0,10,'No user activity found.',0,1);
}

$pdf->Output('user_activity_report.pdf', 'D');

$conn->close();
?>
