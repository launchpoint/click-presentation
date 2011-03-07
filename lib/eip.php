<?

function eip_text_field()
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $url = func_get_arg(1);
  $width="auto";
  if(func_num_args()==3){
    $width= func_get_arg(2);
  }
  $value = $obj->$field;
  if ($obj->errors && array_key_exists($field, $obj->errors) && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $extra = '<script type="text/javascript">$(document).ready(function() {$("#eipedit_'.$obj->id.'_'.$field.'").editable("'.$url.'", 
    {
      submitdata: {id: "'.$obj->id.'", field: "'.$field.'"},
      submit: "Save",
      cancel: "Cancel",
      name: "'.field_name($field).'",      
      width: "'.$width.'"
    });
  });</script>';

//This should be a rollover class color
  return "<div id='eipedit_".$obj->id."_".$field."' class='eip'>".$value."</div>".$extra;
}

function eip_textarea_field()
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $url = func_get_arg(1);
  $width="auto";
  $height="50px";
  if(func_num_args()>=3){
    $width= func_get_arg(2);
  }
  if(func_num_args()>=4){
    $height= func_get_arg(3);
  }
  $value = $obj->$field;
  if ($obj->errors && array_key_exists($field, $obj->errors) && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $extra = '<script type="text/javascript">$(document).ready(function() {$("#eipedit_'.$obj->id.'_'.$field.'").editable("'.$url.'", 
    {
      submitdata: {id: "'.$obj->id.'", field: "'.$field.'"},
      type: "textarea",
      submit: "Save",
      cancel: "Cancel",
      name: "'.field_name($field).'",      
      width: "'.$width.'",
      height: "'.$height.'",
      tooltip: "click to edit"
    });
  });</script>';

//This should be a rollover class color
  return "<div style='display:block; width:{$width}; height:{$height};' id='eipedit_".$obj->id."_".$field."' class='eip'>".$value."</div>".$extra;
}