<?

function field_name($field=null)
{
  global $form_objs, $form_multi,$name_stack;
  if (count($form_objs)==0) click_error("No form object found");
  $name = $name_stack[0];
  for($i=1;$i<count($name_stack);$i++)
  {
  	$name.="[${name_stack[$i]}]";
  }
  $obj = $form_objs[count($form_objs)-1];
  if ($form_multi>0 && $obj->id) $name .= "[$obj->id]";
  if ($field) $name.="[$field]";
  return $name;
}


function fields_for_multi($obj,$namespace=null)
{
  global $form_objs, $form_multi,$name_stack;
  $form_multi++;
  $form_objs[] = $obj;
  if ($namespace===null) $namespace = pluralize($obj->tableized_klass);
  $name_stack[] = $namespace;
}

function fields_for($obj)
{
  global $form_objs, $form_multi,$name_stack;
  $form_objs[] = $obj;
  $name_stack[] = singularize(tableize(get_class($obj)));
}

function end_fields_for()
{
  global $form_objs, $name_stack;
  array_pop($form_objs);
  array_pop($name_stack);
}

function end_fields_for_multi()
{
  global $form_objs, $form_multi,$name_stack;
  $form_multi--;
  array_pop($form_objs);
  array_pop($name_stack);
}

function textarea_field()
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $value = $obj->$field;
  $attrs = array(
    'name'=>field_name($field)
  );
  if ($obj->errors && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $args = func_get_args();
  $s = splice_attrs($attrs, $args);
  $value = htmlentities($value);
  return "<textarea $s>$value</textarea>";
}

function file_field()
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $attrs = array(
    'type'=>'file',
    'name'=>field_name($field)
  );
  if ($obj->errors && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $args = func_get_args();
  $s = splice_attrs($attrs, $args);
  return "<input $s />";
}



function command_field($name)
{
  return '<input type="hidden" name="cmd" value="'.h($name).'"/>';
}

function hidden_field()
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $value = $obj->$field;
  $attrs = array(
    'type'=>'hidden',
    'name'=>field_name($field),
    'value'=>$value
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args);
  return "<input $s />";
}

function date_field() //Aaron - editing this because namespace_for is undefined ?!?
//It becomes readily apparent that this function does not work.  Is this a failing of select_tag???
//It looks like select_tag needs a different test than is_numeric($k) for this to branch properly.
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  if ($obj->$field==null) $obj->$field = time();
  $value = getdate($obj->$field);

//  namespace_for($field.'_parts');
  
  $months=array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
  $arr = array();
  for($i=1;$i<=12;$i++) $arr[] = (object) array('id'=>$i, 'name'=>$months[$i-1]);
//  $s = select_tag(field_name('month'), $arr, $value['mon']);
  $s = select_tag($field."[month]", $arr, $value['mon']);

  $arr = array();
  for($i=1;$i<=31;$i++) $arr[] = (object) array('id'=>$i, 'name'=>$i);
//  $s .= '/'. select_tag(field_name('day'), $arr, $value['mday']);
  $s .= '/'. select_tag($field."[day]", $arr, $value['mday']);

  $arr = array();
  for($i=$value['year']-10;$i<=date('Y')+10;$i++) $arr[] = (object) array('id'=>$i, 'name'=>$i);
//  $s .= '/'.select_tag(field_name('year'), $arr, $value['year']);
  $s .= '/'.select_tag($field."[year]", $arr, $value['year']);
  
//  end_namespace_for();
  
  return $s;
}


function time_field() //Aaron - created
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  if ($obj->$field==null) $obj->$field = time();
  $value = getdate($obj->$field);
  $ampm = 'AM';
  $hours = $value['hours'];
  if ($hours >= 12)
  {
	$hours = $hours - 12;
	$ampm = 'PM';
  }
  if ($value['hours'] == 0) $hours = 12;
//  namespace_for($field.'_parts');
  
  $arr = array();
  for($i=1;$i<=12;$i++) $arr[] = (object) array('id'=>$i, 'name'=>$i);
//  $s = select_tag(field_name('month'), $arr, $value['mon']);
  $s = select_tag($field."[hour]", $arr, $hours);

  $arr = array();
  for($i=0;$i<=5;$i+=5) $arr[] = (object) array('id'=>$i, 'name'=>'0'.$i);
  for($i=10;$i<=55;$i+=5) $arr[] = (object) array('id'=>$i, 'name'=>$i);
//  $s .= '/'. select_tag(field_name('day'), $arr, $value['mday']);
  $s .= ':'. select_tag($field."[minute]", $arr, $value['minutes']);

  $arr = array((object) array('id'=>'AM', 'name'=>'AM'), (object) array('id'=>'PM', 'name'=>'PM'));
//  $s .= '/'.select_tag(field_name('year'), $arr, $value['year']);
  $s .= ' '.select_tag($field."[ampm]", $arr, $ampm);
  
//  end_namespace_for();
  
  return $s;
}


function text_field()
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $value = $obj->$field;
  $attrs = array(
    'type'=>'text',
    'name'=>field_name($field),
    'value'=>$value
  );
  if ($obj->errors && array_key_exists($field, $obj->errors) && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';

  $args = func_get_args();
  $s = splice_attrs($attrs, $args);
  return "<input $s />";
}



function password_field()
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);
  $value = $obj->$field;
  $attrs = array(
    'type'=>'password',
    'name'=>field_name($field),
    'value'=>$value
  );
  if ($obj->errors && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $args = func_get_args();
  $s = splice_attrs($attrs, $args);
  return "<input $s />";
}

function select_field($field,  $options, $value_field='id', $display_field='name')
{
  global $form_objs, $form_multi;
  $obj = $form_objs[count($form_objs)-1];
  $args = func_get_args();
  if(count($args)<2) click_error("Field name and options array are required.");
  if(count($args)<3) $args[] = 'id';
  if(count($args)<4) $args[] = 'name';
  if ($obj->errors && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='')
  {
    $args[] = 'class';
    $args[] = 'error_field';
  }

  $args[0] = field_name($field);
  $args = array_merge(array_slice($args,0,2), array($obj->$field), array_slice($args,2));
  return call_user_func_array('select_tag', $args);
}



function checkbox_field($field)
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);

  $name = field_name($field);
  $attrs = array(
    'type'=>'checkbox',
    'name'=>$name,
    'value'=>1
  );
  if ($obj->errors && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $args = func_get_args();
  $attrs = assemble_attrs($attrs, $args);
  $is_checked = $obj->$field == $attrs['value'];
  $s = splice_attrs($attrs, $args);
  return "<input type='hidden' name='$name' value='0'/><input $s ".( $is_checked ? 'checked="checked"' : '')." />";
}


function radio_field($field)
{
  global $form_objs;
  $obj = $form_objs[count($form_objs)-1];
  $field = func_get_arg(0);

  $name = field_name($field);
  $attrs = array(
    'type'=>'radio',
    'name'=>$name,
    'value'=>func_get_arg(1)
  );
  if ($obj->errors && array_key_exists($field, $obj->errors) && $obj->errors[$field]!='') $attrs['class'] = 'error_field';
  $args = func_get_args();
  $attrs = assemble_attrs($attrs, $args, 2);
  $is_checked = $obj->$field == $attrs['value'];
  $s = splice_attrs($attrs, $args, 2);
  return "<input $s ".( $is_checked ? 'checked="checked"' : '')." />";
}
