<?php
ob_start();
include 'admin.php'; 

include '../api/users/fetch_all.php'; 
include '../api/submission/fetch_all.php'; 
include '../api/task/fetch_all.php'; 
ob_end_clean();

require('fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(34, 139, 34);
        $this->Image('./assets/logo.png', 10, 10, 30);
        $this->Cell(0, 10, 'EcoQuest Sustainability Report', 0, 1, 'C');
        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(100);
        $this->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i'), 0, 1, 'C');
        $this->Ln(5);
        $this->Line(10, 30, 200, 30); 
        $this->Ln(10);
    }

    function FancyTable($header, $data) {
        $this->SetFillColor(232, 232, 232);
        $this->SetFont('Arial', 'B', 12);
        foreach($header as $col) $this->Cell(47, 7, $col, 1, 0, 'C', true);
        $this->Ln();
        $this->SetFont('Arial', '', 12);
        foreach($data as $row) {
            foreach($row as $col) $this->Cell(47, 6, $col, 1);
            $this->Ln();
        }
    }
}

$pdf = new PDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'System Overview', 0, 1);
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(95, 10, "Total Users: " . $totalUser['count'], 1, 0);
$pdf->Cell(95, 10, "Submission Success Rate: " . $successRate . "%", 1, 1);
$pdf->Cell(95, 10, "Total Submissions: " . $totalSubmission['count'], 1, 0);
$pdf->Cell(95, 10, "Tasks Completed: " . $completedTask['count'], 1, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Reward Inventory Status', 0, 1);
$header = array('Metric', 'Value');
$rewardData = [
    ['Total Rewards', $totalResult['count']],
    ['Active Rewards', $activeResult['count']],
    ['Low Stock (<10)', $lowStockResult['count']],
    ['Claimed Rate', $claimedPercentage['count'] . '%']
];

foreach($rewardData as $row) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 8, $row[0], 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 8, $row[1], 1, 1);
}
$pdf->Ln(10);


$pdf->SetX(10); 

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Impact by Goal Type (Approved Tasks)', 0, 1, 'L');

$availableWidth = $pdf->GetPageWidth() - 20;
$colWidth = $availableWidth / 4;

$pdf->SetFillColor(200, 255, 200);

$pdf->SetX(10);
$pdf->Cell($colWidth, 8, 'Plastic', 1, 0, 'C', true);
$pdf->Cell($colWidth, 8, 'Trash', 1, 0, 'C', true);
$pdf->Cell($colWidth, 8, 'Electric', 1, 0, 'C', true);
$pdf->Cell($colWidth, 8, 'Carbon', 1, 1, 'C', true); 

$pdf->SetX(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($colWidth, 8, ($goalCounts['plastic'] ?? 0), 1, 0, 'C');
$pdf->Cell($colWidth, 8, ($goalCounts['trash'] ?? 0), 1, 0, 'C');
$pdf->Cell($colWidth, 8, ($goalCounts['electric'] ?? 0), 1, 0, 'C');
$pdf->Cell($colWidth, 8, ($goalCounts['carbon'] ?? 0), 1, 1, 'C');

$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Submission Status Breakdown', 0, 1, 'L');

$w = $pdf->GetPageWidth() - 20;
$colWidth = $w / 3; 

$pdf->SetFillColor(232, 232, 232); 
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX(10);
$pdf->Cell($colWidth, 8, 'Status', 1, 0, 'C', true);
$pdf->Cell($colWidth, 8, 'Total Count', 1, 0, 'C', true);
$pdf->Cell($colWidth, 8, 'Percentage', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 12);

$pdf->SetX(10);
$pdf->Cell($colWidth, 8, 'Approved', 1, 0, 'C');
$pdf->Cell($colWidth, 8, $appCount, 1, 0, 'C');
$pdf->Cell($colWidth, 8, round($pApp, 1) . '%', 1, 1, 'C');

$pdf->SetX(10);
$pdf->Cell($colWidth, 8, 'Pending', 1, 0, 'C');
$pdf->Cell($colWidth, 8, $penCount, 1, 0, 'C');
$pdf->Cell($colWidth, 8, round($pPen, 1) . '%', 1, 1, 'C');

$pdf->SetX(10);
$pdf->Cell($colWidth, 8, 'Rejected', 1, 0, 'C');
$pdf->Cell($colWidth, 8, $rejCount, 1, 0, 'C');
$pdf->Cell($colWidth, 8, round($pRej, 1) . '%', 1, 1, 'C');

$pdf->Ln(10);

$pdf->Output();
?>