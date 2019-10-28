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

    //        pr($clinics);
    //        die;
    $clinic_result_all = [];
    foreach ($clinics as $k => $clinic) {

//      pr ($clinic);die;

      $clinic_html = file_get_html($clinic->url);

//      echo '<a href="' . $clinic->url . '">Clinic</a>';
      $clinic_result ['slug'] = $clinic->slug;
      $clinic_result ['title'] = $clinic->title;

      foreach ($clinic_html->find(".planWrapperSelected") as $resultArea) {

//        echo $resultArea;
//        die;


        $clinic_plan_data = [];

        foreach ($resultArea->find(".singlePlan") as $single) {
          $clinic_plan['plan_id'] = explode('/', $single->find(".planName", 0)->find('a', 0)->href)[5];
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


            $plan_item['planTag']['planTagItems'] = [];
            $plan_item['planTag']['trigger'] = $facilityItem->find('.planTag', 0)->find('h3.trigger', 0)->plaintext;
            foreach ($facilityItem->find('.planTag', 0)->find('.planTagBody ul.tagList li') as $planTags) {
              $plan_item['planTag']['planTagItems'][] = $planTags->plaintext;
            }

            $plan_item['planRec']['planRecItems'] = [];
            $plan_item['planRec']['trigger'] = $facilityItem->find('.planRec', 0)->find('h3.trigger', 0)->plaintext;
            foreach ($facilityItem->find('.planRec', 0)->find('.planRecBody ul li') as $planRecord) {
              $plan_item['planRec']['planRecItems'][] = $planRecord->plaintext;
            }

            $plan_item['planFeature']['trigger'] = $facilityItem->find('.planFeature', 0)->find('h3.trigger', 0)->plaintext;
            $plan_item['planFeature']['planFeatureBody'] = $facilityItem->find('.planFeature', 0)->find('.planFeatureBody', 0)->plaintext;
            $plan_item['planCheckDetail']['trigger'] = $facilityItem->find('.planCheckDetail', 0)->find('h3.trigger', 0)->plaintext;
            $plan_item['planCheckDetail']['planDetailBody'] = $facilityItem->find('.planCheckDetail', 0)->find('.planDetailBody', 0)->innertext;

            $clinic_plan['clinic_plans'] = $plan_item;
          }

          $clinic_plan_data[] = $clinic_plan;

        }

        $clinic_result['clinics'] = $clinic_plan_data;

      }

      $clinic_result_all[] = $clinic_result;

      $fp = fopen('clinics/' . $clinic->slug . '--data.json', 'w');
      if (fwrite($fp, json_encode($clinic_result_all))) {
        echo $k . '-done <br>';
      }
      fclose($fp);

      die;
    }


    ?>

</body>

</html>