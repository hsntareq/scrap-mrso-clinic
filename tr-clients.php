<?php

include('vendor\autoload.php');

use Stichoza\GoogleTranslate\GoogleTranslate;

include('simple_html_dom.php');

$tr = new GoogleTranslate('en');

$clinics = json_decode(file_get_contents('4.json'));

$clinic_result_all = [];
foreach ($clinics as $k => $clinic) {

  $clinic_html = file_get_html($clinic->url);
  $clinic_result['slug'] = $clinic->slug;
  $clinic_result['title'] = $clinic->title;

  foreach ($clinic_html->find(".planWrapperSelected") as $resultArea) {

    $clinic_plan_data = [];

    foreach ($resultArea->find(".singlePlan") as $single) {
      $clinic_plan['plan_id'] = explode('/', $single->find(".planName", 0)->find('a', 0)->href)[5];
      $clinic_plan['plan_url'] = 'https://www.mrso.jp' . $single->find(".planName", 0)->find('a', 0)->href;

      $facilityItems = file_get_html($clinic_plan['plan_url']);

      foreach ($facilityItems->find('#mainContents') as $facilityItem) {

        $plan_item['facilityName'] = $facilityItem->find('.faciliyHeadSimple', 0)->find('.facilityName', 0)->plaintext;
        $plan_item['facilityAddress'] = $facilityItem->find('.faciliyHeadSimple', 0)->find('.facilityAddress', 0)->plaintext;
        $plan_item['facilityStation'] = $facilityItem->find('.faciliyHeadSimple', 0)->find('.facilityStation', 0)->plaintext;
        $plan_item['planName'] = $facilityItem->find('.planName h1', 0)->plaintext;
        $plan_item['resultPrice'] = $facilityItem->find('.resultPrice', 0)->plaintext;

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
}
pr(clinic_result_all);die;
$fp = fopen('all-data.json', 'w');
fwrite($fp, json_encode($clinic_result_all));
fclose($fp);
