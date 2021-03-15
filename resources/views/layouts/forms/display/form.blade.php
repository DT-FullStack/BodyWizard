<?php 
if (isset($form)){
  $formUID = $form->getkey();
  $formId = $form->form_id;
  $data = json_encode($form->full_json);
  $sections = json_encode($form->sections);
  $settings = $form->settings;
  $name = $form->form_name;
}else{ throw \Exception('Form not given');}

$mode = isset($mode) ? $mode : request('mode','display');
$action = isset($action) ? $action : request('action','FormEle.submit');
Log::info(compact('mode','action'));
?>
<div class='form_proxy' data-json='{{$form->toJson()}}' data-settings='{{json_encode($settings)}}' data-mode='{{$mode}}' data-action='{{$action}}'></div>