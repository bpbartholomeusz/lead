<?php
// Include Moodle config file to initialize Moodle environment.
require_once(__DIR__ . '/../../config.php');

// Include Moodle's PDF library (pdflib.php).
require_once($CFG->libdir . '/pdflib.php');

// Ensure the user is logged in (optional if you want to restrict this to logged-in users).
require_login();

// Set headers to prevent Moodle from outputting any additional content.
header('Content-Type: application/pdf');

// Create a new PDF document using Moodle's PDF library.
$pdf = new pdf();
$pdf->AddPage();
$pdf->Write(0, 'Test PDF generation using Moodle\'s PDF library');

// Define the file path for saving the PDF.
$pdf_filepath = '/tmp/test_moodle_pdf.pdf'; // Save to /tmp for testing
$pdf->Output($pdf_filepath, 'F'); // 'F' means save to file

// Check if the file was created successfully.
if (file_exists($pdf_filepath)) {
  echo "PDF file successfully generated: " . $pdf_filepath;
} else {
  echo "Failed to generate PDF.";
}
