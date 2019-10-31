<?php
include('vendor\autoload.php');
use Stichoza\GoogleTranslate\GoogleTranslate;

$tr = new GoogleTranslate('en'); 

include('simple_html_dom.php');

$data = file_get_contents('mrso-clinic-data.json');

pr($data);
die;
foreach($data as $k=>$clinic){
    pr($clinic);die;
    echo ($k+1).'. '.$tr->translate($clinic->title).'<br>';
}
