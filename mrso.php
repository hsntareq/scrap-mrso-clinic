<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clinic List</title>
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
</head>

<body>
    <?php
    include('simple_html_dom.php');

    $j = 12;

    $clinics = [];
    for ($i = 1; $i <= $j; $i++) {
        
        $html = file_get_html('https://www.mrso.jp/tokyo/?page=' . $i);

        foreach ($html->find("#resultArea") as $resultArea) {

            foreach ($resultArea->find('section.gray') as $result_item) {

                $clinic['slug'] = explode('/', $result_item->find('a.facilityName', 0)->href)[3];
                $clinic['title'] = $result_item->find("h2.sp-only", 0)->plaintext;
                $clinic['url'] = 'https://www.mrso.jp' . $result_item->find('a.facilityName', 0)->href;

                $clinics[] = $clinic;
            }

        }
    }


    // $fp = fopen('clinics_data.json', 'w');
    // fwrite($fp, json_encode($clinics));
    // fclose($fp);


    ?>

</body>

</html>