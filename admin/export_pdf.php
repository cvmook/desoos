<?php
// Including the Database class and creating an instance
require("../model/Database.php");
$Database = new Database;

// Including FPDF library for PDF generation
require('../fpdf/fpdf.php');

// Including the Event class and creating an instance
include('../model/Event.php');
$Event = new Event;

// Creating a new FPDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Establishing a database connection
$connection = $Database->DbConnect();

// Code for printing the table heading
$pdf->SetFont('Arial', 'B', 12);
$query1 = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='empdata' AND `TABLE_NAME`='registrations'";
$result = $Database->DbConnect()->query($query1);
$header = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($header as $heading) {
    foreach ($heading as $column_heading)
        $pdf->Cell(46, 12, $column_heading, 1);
}

// Code for printing data
$eventId = $_POST['eventId'];
$query = "SELECT r.registrationname, r.registrationphone
FROM `events-registrations` er
INNER JOIN `registrations` r ON er.registration_id = r.registration_id
WHERE er.event_id = '$eventId'";

$result2 = mysqli_query($connection, $query);

$num_rows2 = mysqli_num_rows($result2);

if ($num_rows2 > 0) {
    // Set font and print the title for the PDF
    $pdf->SetFont('Arial', 'b', 30);
    $pdf->Ln(20);  // Add space before the title
    $pdf->Cell(0, 12, "Deelnemerslijst", 0, 1, 'C');  // Center the title
    $pdf->SetFont('Arial', '', 12);

    // Loop through each row of data and print it in the PDF
    foreach ($result2 as $row) {
        $pdf->Ln();
        $pdf->Cell(10, 10, '', 1, 0);
        foreach ($row as $column) {
            $pdf->Cell(90, 10, $column, 1);
        }
    }

    // Output the generated PDF
    $pdf->Output();
} else {
    // Display an error message if there are no registrations
    echo "<div class='alert alert-danger' role='alert'><h1> Er zijn geen aanmeldingen beschikbaar </h1></div> ";
}
