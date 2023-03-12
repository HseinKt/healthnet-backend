<?php

include("connection_db.php");

//require the TCPDF library
require_once('tcpdf/tcpdf.php');
$pdf = new TCPDF();

// Set the document properties, 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Invoice');
$pdf->SetSubject('Invoice');

// Add a page to the document:
$pdf->AddPage();    

$pdf->SetFont('dejavusans', '', 12);

$user_id = $_POST['user_id'];

$sql_query = $mysqli -> prepare('SELECT total_amount FROM invoices WHERE user_id = ?');
$sql_query -> bind_param('i', $user_id);
$sql_query -> execute();
$result = $sql_query -> get_result();
$response = [];

while($row = $result -> fetch_array(MYSQLI_NUM)) {
    $arr = [];
    $arr['user_id'] = $row[0];
    // Add content to the page, such as the invoice details:
    
    // details about the user
    $sql_query2 = $mysqli -> prepare('SELECT name, email 
    from users u
    JOIN invoices i ON i.user_id = u.id 
    WHERE user_id = ?');
    $sql_query2 -> bind_param('i', $user_id);
    $sql_query2 -> execute();
    $result2 = $sql_query2 -> get_result();

    while($row2 = $result2 -> fetch_array(MYSQLI_NUM)) {
        $arr2 =[];
        $arr2['name'] = $row2[0];
        $arr2['email'] = $row2[1];

        $pdf->Write(0, 'Invoice Details', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(10);
        $pdf->Write(0, 'Hello Mr/Mrs '.$arr2['name'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(10);
        $pdf->Write(0, 'this is your email : '.$arr2['email'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(10);
        $pdf->Write(0, 'Total amount : '.$arr['user_id'] .' $', '', 0, 'L', true, 0, false, false, 0);
    }

    array_push($response, $arr);
}



// Output the PDF:
$pdf->Output('invoice.pdf', 'D');

echo json_encode($response);

?>


