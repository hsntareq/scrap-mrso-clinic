<?php
include('vendor\autoload.php');
use Stichoza\GoogleTranslate\GoogleTranslate;

$tr = new GoogleTranslate('en'); 

include('simple_html_dom.php');

$data = json_decode(file_get_contents('all-clinic-data.json'));

// pr(json_decode($data));

foreach($data as $k=>$clinic){
    echo ($k+1).'. '.$tr->translate($clinic->title).'<br>';
}
