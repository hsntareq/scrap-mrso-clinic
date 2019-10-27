<?php
function printExcel($data = [], $header = [], $options = [])
{
  try {
    set_time_limit('0');
    ini_set('memory_limit', '-1');
    require ROOT . DS . 'vendor' . DS . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel.php';
    require ROOT . DS . 'vendor' . DS . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel' . DS . 'IOFactory.php';

    $book = new \PHPExcel();
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

    if (!empty($data)) {
      foreach ($data as $row => $value) {
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
  } catch (\Exception $ex) {

  }
}
