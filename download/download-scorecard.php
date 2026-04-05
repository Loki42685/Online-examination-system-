<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
ob_start();

include("config.php");

// ✅ Make sure this path matches your folder
require(__DIR__ . '/fpdf186/fpdf.php');

if(!isset($_SESSION['student_id'])){
    header("Location: student-login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

/* 🎓 Student info */
$student = mysqli_query($conn,"SELECT name FROM students WHERE id='$student_id'");
$data = mysqli_fetch_assoc($student);
$name = $data['name'];

/* 📊 Fetch results */
$query = mysqli_query($conn,"
SELECT results.*, exams.exam_name
FROM results
JOIN exams ON results.exam_id = exams.id
WHERE results.student_id='$student_id'
AND results.is_published=1
");

/* Create PDF */
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetDrawColor(200,200,200);
$pdf->Rect(5,5,200,287); // full page border
/* 🖤 BIG HEADER BOX */
$pdf->SetFillColor(0,0,0);
$pdf->Rect(0, 0, 210, 35, 'F'); // FULL WIDTH BLACK BOX

/* 🎓 REPORT CARD TITLE */
$pdf->SetY(10);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',22); // bigger font

$pdf->Cell(0,10,'REPORT CARD',0,1,'C');

/* ➖ LINE UNDER TITLE */
$pdf->SetDrawColor(255,255,255); // white line
$pdf->SetLineWidth(0.5);
$pdf->Line(60,22,150,22); // centered line

/* 🏷 PROJECT NAME */
$pdf->SetFont('Arial','',10);
$pdf->SetY(24);
$pdf->Cell(0,6,'ONLINE EXAMINATION SYSTEM',0,1,'C');

/* RESET FOR BODY */
$pdf->Ln(20);
$pdf->SetTextColor(0,0,0);
/* 👤 NAME + ID (STACKED) */
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',12);

$pdf->Cell(0,8,'Name: '.$name,0,1);
$pdf->Cell(0,8,'Student ID: '.$student_id,0,1);

$pdf->Ln(8);

/* 🖤 SUBJECT HEADER BAR */
$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',12);

$pdf->Cell(70,10,'Subject',0,0,'L',true);
$pdf->Cell(30,10,'Marks',0,0,'C',true);
$pdf->Cell(40,10,'Percentage',0,0,'C',true);
$pdf->Cell(30,10,'Grade',0,1,'C',true);

$pdf->Ln(2);

/* 📋 DATA ROWS */
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0,0,0);

$total_percentage = 0;
$count = 0;

if(mysqli_num_rows($query) == 0){
    $pdf->Cell(0,10,'No results available',0,1,'C');
}else{

    while($row = mysqli_fetch_assoc($query)){

        $percentage = $row['percentage'];

        $pdf->Cell(70,10,$row['exam_name'],0,0);
        $pdf->Cell(30,10,$row['total_marks'],0,0,'C');
        $pdf->Cell(40,10,number_format($percentage,1).'%',0,0,'C');
        $pdf->Cell(30,10,$row['grade'],0,1,'C');

        /* light divider */
        $pdf->SetDrawColor(200,200,200);
        $pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());

        $total_percentage += $percentage;
        $count++;
    }
}

/* 🖤 OVERALL SECTION BAR */
$pdf->Ln(10);

$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'OVERALL PERFORMANCE',0,1,'C',true);

/* RESULT TEXT */
$pdf->SetTextColor(0,0,0);

$avg = ($count > 0) ? $total_percentage / $count : 0;

$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'Overall Percentage: '.number_format($avg,1).'%',0,1,'C');
if($avg >= 40){
    $status = "PASS";
    $pdf->SetTextColor(0,128,0);
}else{
    $status = "FAIL";
    $pdf->SetTextColor(255,0,0);
}

$pdf->Cell(0,10,'Result Status: '.$status,0,1,'C');

$pdf->SetTextColor(0,0,0);
/* ✍️ SIGNATURE */
$pdf->Ln(15);

$pdf->SetFont('Arial','',11);

/* 📅 FOOTER */
$pdf->Ln(10);

$pdf->SetFont('Arial','I',9);
$pdf->SetTextColor(120,120,120);
$pdf->Cell(0,8,'Generated on '.date("d-m-Y"),0,0,'C');

/* ✅ Output PDF */
ob_end_clean();
$pdf->Output('D', 'scorecard.pdf');
?>