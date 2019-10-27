<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clinic Plans</title>
</head>

<body>
    <?php
    include('simple_html_dom.php');

    // $clinics = json_decode(file_get_contents('clinics_data.json'));
    // pr($clinics);die;

    $clinics = json_decode(file_get_contents('clinics_data.json'));


    foreach ($clinics as $clinic) {

//      pr ($clinic);die;

      $clinic_html = file_get_html($clinic->url);

      echo '<a href="' . $clinic->url . '">Clinic</a>';
      // $plan_items = [];

      foreach ($clinic_html->find(".planWrapperSelected") as $resultArea) {

        foreach ($resultArea->find(".singlePlan") as $single) {

          // echo $single;die;

          $clinic_plan['plan_name'] = $single->find(".planName", 0)->planetext;

          $clinic_plan['plan_url'] = 'https://www.mrso.jp' . $single->find(".planName", 0)->find('a', 0)->href;


          $facilityItems = file_get_html($clinic_plan['plan_url']);


          foreach ($facilityItems->find('#mainContents') as $facilityItem) {

            // echo ($facilityItem);
            $plan_item['facilityName'] = $facilityItem->find('.faciliyHeadSimple', 0)->find('.facilityName', 0)->plaintext;
             $plan_item['facilityAddress'] = $facilityItem->find('.faciliyHeadSimple', 0)->find('.facilityAddress', 0)->plaintext;
             $plan_item['facilityStation'] = $facilityItem->find('.faciliyHeadSimple', 0)->find('.facilityStation', 0)->plaintext;
             $plan_item['planName'] = $facilityItem->find('.planName h1', 0)->plaintext;
             $plan_item['resultPrice'] = $facilityItem->find('.resultPrice', 0)->plaintext; //need to separate

             $plan_item['planTime']['trigger'] = $facilityItem->find('.planFeature', 0)->find('h3.trigger', 0)->plaintext;
             $plan_item['planTime']['planTimeBody'] = $facilityItem->find('.planTimeBody', 0)->plaintext;


             $plan_item['planTag']['trigger'] = $facilityItem->find('.planTag', 0)->find('h3.trigger', 0)->plaintext;
             foreach ($facilityItem->find('.planTag', 0)->find('.planTagBody ul') as $planRecord) {
                 $plan_item['planTag']['planTagItems'][] = $planRecord->plaintext;
             }


             $plan_item['planRec']['trigger'] = $facilityItem->find('.planRec', 0)->find('h3.trigger', 0)->plaintext;
             foreach ($facilityItem->find('.planRec', 0)->find('.planRecBody ul') as $planRecord) {
                 $plan_item['planRec']['planRecItems'][] = $planRecord->plaintext;
             }

             $plan_item['planFeature']['trigger'] = $facilityItem->find('.planFeature', 0)->find('h3.trigger', 0)->plaintext;
             $plan_item['planFeature']['planFeatureBody'] = $facilityItem->find('.planFeature', 0)->find('.planFeatureBody', 0)->plaintext;
             $plan_item['planCheckDetail']['trigger'] = $facilityItem->find('.planCheckDetail', 0)->find('h3.trigger', 0)->plaintext;
             $plan_item['planCheckDetail']['planDetailBody'] = $facilityItem->find('.planCheckDetail', 0)->find('#postPlanTable', 0)->innertext;

            $clinic_plan['clinic_plans'] = $plan_item;
          }
        }


      }

    }

    pr($clinic_plan);
    die;


    $fp = fopen('all-clinics.json', 'w');
    fwrite($fp, json_encode($clinic_plan));
    fclose($fp);

    ?>

</body>

</html>