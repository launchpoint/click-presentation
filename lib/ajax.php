<?

function autocheckbox_field($field)
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $url = func_get_arg(1);
  $validator="";
  if(func_num_args()==3){
    $validator= func_get_arg(2);
  }
  $is_checked = $obj->$field == 1;
  
  $name = field_name($field);
  $attrs = array(
    'type'=>'checkbox',
    'name'=>$name,
    'value'=>1
  );
  if ($obj->errors && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $args = func_get_args();
  array_shift($args);
  array_shift($args);
  $s = splice_attrs($attrs, $args,2);
  $extra =    '<script type="text/javascript"> 
        $(document).ready(function() { 
            $("#acb_'.$obj->id.'_'.$field.'").ajaxForm();
            });
    </script>';
  if($validator)
  {
  return "<form id='acb_".$obj->id."_".$field."' action='$url' style='display:inline'><input type='hidden' name='id' value='$obj->id'/><input type='hidden' name='$name' value='0'/><input id='acb_".$obj->id."_".$field."_cb' $s ".( $is_checked ? 'checked="checked"' : '')." onclick='if(".$validator.")$(\"#acb_".$obj->id."_".$field."\").ajaxSubmit();'/></form>".$extra;
  
  }
  else
  {
  return "<form id='acb_".$obj->id."_".$field."' action='$url' style='display:inline'><input type='hidden' name='id' value='$obj->id'/><input type='hidden' name='$name' value='0'/><input $s ".( $is_checked ? 'checked="checked"' : '')." onclick='$(\"#acb_".$obj->id."_".$field."\").ajaxSubmit();'/></form>".$extra;
  }
}

function arb_field($field)
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $url = func_get_arg(1);
  $values = func_get_arg(2);
  $name = field_name($field);
  if ($obj->errors && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $args = func_get_args();
  array_shift($args);
  array_shift($args);
  array_shift($args);
  $extra =    '<script type="text/javascript"> 
        $(document).ready(function() { 
            
            $("#arb_'.$obj->id.'_'.$field.'").ajaxForm();
            });
    </script>';
  $ret = "<form id='arb_".$obj->id."_".$field."' action='$url' style='display:inline'><input type='hidden' name='id' value='$obj->id'/>";
  foreach($values as $value)
  {
    $attrs = array(
      'type'=>'radio',
      'name'=>$name,
      'value'=>$value[1]
    );  
    $is_checked=false;
    if($obj->$field == $value[1])
    {$is_checked=true;}
    $s = splice_attrs($attrs, $args,2);
    $ret = $ret."<input $s ".( $is_checked ? 'checked="checked"' : '')." class='arb_field' onclick='$(\"#arb_".$obj->id."_".$field."\").ajaxSubmit();'/>".$value[0];  
  }
  $ret = $ret."</form>".$extra;
  return $ret;
}