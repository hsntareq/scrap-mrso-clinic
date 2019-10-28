<?php
//phpinfo();die;
include('simple_html_dom.php');

$data = file_get_contents('clinic/hayashi-neurosurgery-clinic--data.json');

pr(json_decode($data));
