<?php
require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
// Set document properties
$spreadsheet->getProperties()->setCreator('PhpOffice')
  ->setLastModifiedBy('PhpOffice')
  ->setTitle('Office 2007 XLSX Test Document')
  ->setSubject('Office 2007 XLSX Test Document')
  ->setDescription('PhpOffice')
  ->setKeywords('PhpOffice')
  ->setCategory('PhpOffice');


// Add some data

$clinics = json_decode(file_get_contents('clinics_data.json'));
foreach($clinics as $clinic){
  echo '<pre>';
  print_r($clinic);
  echo '</pre>';
}

$spreadsheet->setActiveSheetIndex(0)
  ->setCellValue('A1', 'Hello');

$spreadsheet->setActiveSheetIndex(0)
  ->setCellValue('B2', 'Hasan');
// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('URL Added');
$spreadsheet->createSheet();


// Add some data
$spreadsheet->setActiveSheetIndex(1)
  ->setCellValue('A1', 'world!')
  ->setCellValue('A2', 'What');


// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('URL Removed');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet






$spreadsheet->setActiveSheetIndex(0);




// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

die;


$options = [
  ['key' => 'slug', 'title' => 'slug'],
  ['key' => 'title', 'title' => 'title'],
  ['key' => 'url', 'title' => 'url']
];
set_time_limit('0');
ini_set('memory_limit', '-1');

//  $book = new \PHPExcel();
$book = new Spreadsheet();
$title = isset($options['name']) ? $options['name'] : 'excel';
$book->getActiveSheet()->setTitle('Sheet 1');
$sheet = $book->getActiveSheet();

$style = array(
  'font' => array('bold' => true, 'size' => 12),
  'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
);
$cur_index = 1;
if (!empty($header)) {
  $t_header = count($header);
//                if(!empty($options['name'])){
//
//                    $sheet->setCellValueByColumnAndRow(0,$cur_index, $options['name']);
//                    $sheet->mergeCellsByColumnAndRow(0,$cur_index,$t_header,$cur_index);
//                    $sheet->getStyleByColumnAndRow(0, $cur_index)->applyFromArray($style);
//                    $cur_index++;
//                }
  if (!empty($options['title'])) {
    foreach ($options['title'] as $titles) {
      $sheet->setCellValueByColumnAndRow(0, $cur_index, $titles);
      $sheet->mergeCellsByColumnAndRow(0, $cur_index, $t_header, $cur_index);
      $sheet->getStyleByColumnAndRow(0, $cur_index)->applyFromArray($style);
      $cur_index++;
    }

  }
  foreach ($header as $key => $headTitle) {
    $sheet->setCellValueByColumnAndRow($key, $cur_index, $headTitle['title']);
  }
  $cur_index++;
}

if (!empty($clinics)) {
  foreach ($clinics as $row => $value) {
    foreach ($header as $col => $headTitle) {
      $sheet->setCellValueByColumnAndRow($col, $cur_index, ($headTitle['key'] == 'si' ? Number::format($row + 1) : (isset($value[$headTitle['key']]) ? $value[$headTitle['key']] : '')));

    }
    $cur_index++;
  }
}
//footer settings
if (!empty($options['footer'])) {
  foreach ($options['footer'] as $footers) {
    foreach ($footers as $col => $footer) {
      $sheet->setCellValueByColumnAndRow($col, $cur_index, $footer);
      $sheet->getStyleByColumnAndRow($col, $cur_index)->applyFromArray($style);
    }
    $cur_index++;
  }
}


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\".$title.xls\"");
header("Cache-Control: max-age=0");
$writer = \PHPExcel_IOFactory::createWriter($book, 'Excel2007');

if (ob_get_contents()) ob_end_clean();
//ob_end_clean();

$writer->save('php://output');
exit;


/*
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx');*/
