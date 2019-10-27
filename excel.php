<?php
require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$clinics = json_decode(file_get_contents('clinics_data.json'), true);

echo '<pre>';
print_r($clinics);

printExcel($clinics, [
  ['key' => 'slug', 'title' => 'শাখা'],
  ['key' => 'title', 'title' => 'শাখা'],
  ['key' => 'url', 'title' => 'শাখা'],
], []);


function printExcel($data = [], $header = [], $options = [])
{
  try {
    set_time_limit('0');
    ini_set('memory_limit', '-1');

    $book = new Spreadsheet();
    $title = isset($options['name']) ? $options['name'] : 'excel';
    $book->getActiveSheet()->setTitle('Sheet 1');
    $sheet = $book->getActiveSheet();

    $style = array(
      'font' => array('bold' => true, 'size' => 12),
      'alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,),
    );
    $cur_index = 1;
    if (!empty($header)) {
      $t_header = count($header);
      if (!empty($options['title'])) {
        foreach ($options['title'] as $titles) {
          $sheet->setCellValueByColumnAndRow(1, $cur_index, $titles);
          $sheet->mergeCellsByColumnAndRow(1, $cur_index, $t_header, $cur_index);
          $sheet->getStyleByColumnAndRow(1, $cur_index)->applyFromArray($style);
          $cur_index++;
        }

      }
      foreach ($header as $key => $headTitle) {
        $sheet->setCellValueByColumnAndRow($key + 1, $cur_index, $headTitle['title']);
      }
      $cur_index++;
    }

    if (!empty($data)) {
      foreach ($data as $row => $value) {
        foreach ($header as $col => $headTitle) {
          $sheet->setCellValueByColumnAndRow($col + 1, $cur_index, ($headTitle['key'] == 'si' ? Number::format($row + 1) : (isset($value[$headTitle['key']]) ? $value[$headTitle['key']] : '')));

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
    $writer = new Xlsx($book);

    if (ob_get_contents()) ob_end_clean();
    //ob_end_clean();

    $writer->save('php://output');
    exit;
  } catch (\Exception $ex) {
    pr($ex->getMessage());
  }
}