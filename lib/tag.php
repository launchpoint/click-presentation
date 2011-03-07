<?


function image_tag($url)
{
  $attrs = array(
    'src'=>$url,
    'alt'=>'image'
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args);
  return "<img $s />";
}

function submit_tag($text)
{
  $attrs = array(
    'type'=>'submit',
    'name'=>'commit'
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args);
  return "<button $s>".se($text)."</button>";
}

function js_button_tag($text, $onclick)
{
	$attrs = array(
		'type'=>'button',
		'name'=>'link',
		'value'=>$text,
		'onclick'=>$onclick
	);
	$args = func_get_args();
	// $args = array();
	$s = splice_attrs($attrs, $args, 2);
	return "<input $s/>";
}

function image_button_tag($src)
{
  $attrs = array(
    'type'=>'image',
    'name'=>'commit',
    'src'=>$src
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args);
  return "<input $s/>";
}


function select_tag($name, $options, $defaults=null, $value_field='id', $display_field='name')
{
  $defaults = (!is_array($defaults)) ? (array($defaults)) : ($defaults);
  $default_values = array();
  foreach($defaults as $k=>$v) 
  {
    if(is_object($v))
    {
      $default_values[] = $v->$value_field;
    } else {
      $default_values[] = $v;
    }
  }
	$s="";
	$attrs=array();
	$args = func_get_args();
	$attrs = splice_attrs($attrs, $args, 5);
	$s.= "<select name=\"$name\" $attrs>";
	foreach($options as $k=>$v)
	{
		if (is_numeric($k))
		{
		  if(is_object($v->$value_field)) click_error("select_tag() is expecting $value_field to be a string or int, but it's an object");
		  if(!is_string($v->$display_field) && !is_numeric($v->$display_field)) click_error("select_tag() is expecting $display_field to be a string, but it's not", array($options, $v, $v->$display_field));
      $value = $v->$value_field;
      $display = h($v->$display_field);
    } else {
      $value = $k;
      $display = h($v);
	  }
		$s.= option_tag($value, $display, $default_values); 
	}
	$s.="</select>";
	return $s;
}

function checklist_tag($name, $options, $defaults=null, $value_field='id', $display_field='name')
{
  $defaults = (!is_array($defaults)) ? (array($defaults)) : ($defaults);
  $default_values = array();
  foreach($defaults as $k=>$v) 
  {
    if(is_object($v))
    {
      $default_values[] = $v->$value_field;
    } else {
      $default_values[] = $v;
    }
  }
	$s="";
	$attrs=array();
	$args = func_get_args();
	$attrs = splice_attrs($attrs, $args, 5);
	foreach($options as $k=>$v)
	{
		if (is_numeric($k))
		{
		  if(is_object($v->$value_field)) click_error("select_tag() is expecting $value_field to be a string or int, but it's an object");
		  if(!is_string($v->$display_field) && !is_numeric($v->$display_field)) click_error("select_tag() is expecting $display_field to be a string, but it's not", array($options, $v, $v->$display_field));
      $value = $v->$value_field;
      $display = h($v->$display_field);
    } else {
      $value = $k;
      $display = h($v);
	  }
    $is_selected = array_search($value, $default_values)!==FALSE;

		$s.= "<input name=\"$name\" type=\"checkbox\" value=\"".h($value)."\"" . (($is_selected) ? ' checked' : '') . " $attrs/>$display<br/>";
	}
	return $s;
}

function option_tag($value, $display, $selected_values = array())
{
  if(!is_array($selected_values)) $selected_values = array($selected_values);
  $is_selected = array_search($value, $selected_values)!==FALSE;

  return "<option value=\"".h($value)."\"" . (($is_selected) ? ' selected="selected"' : '') . ">$display</option>";
}

function command_tag($name)
{
  echo "<input type='hidden' name='cmd' value='".h($name)."'/>";
}

function checkbox_tag($name, $value=1, $is_checked=false)
{
  $attrs = array(
    'type'=>'checkbox',
    'name'=>$name,
    'value'=>$value
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args,3);

  return "<input type='hidden' name='$name' value='0'/><input $s ".( $is_checked ? 'checked="checked"' : '')." />";
}


function hidden_tag($name, $value=1)
{
  $attrs = array(
    'type'=>'hidden',
    'name'=>$name,
    'value'=>$value
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args,2);

  return "<input type='hidden' name='$name' value='$value'/>";
}

function text_tag($name, $value='')
{
  $attrs = array(
    'type'=>'text',
    'name'=>$name,
    'value'=>$value,
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args,2);
  return "<input $s />";
}

function textarea_tag($name, $value='')
{
  $value = $value;
  $attrs = array(
    'name'=>$name
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args,2);
  $value = htmlentities($value);
  return "<textarea $s>$value</textarea>";
}
