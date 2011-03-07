<?

function link_to($text, $path)
{
  $attrs = array(
    'href'=>$path
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args,2);
  $text = h($text);
	return "<a $s >$text</a>";
}

function button_to($text, $path)
{
  $attrs = array(
    'class'=>'button',
    'onclick'=>"document.location='".j($path)."';",
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args,2);
  $text = se($text);
  $html = "<div $s><a href='".h($path)."' onclick='return false;'>$text</a></div>";
  return $html;
}



function mail_to($email, $text=null)
{
	$start=2;
	if ($text==null)
	{
		$text=$email;
		$start=1;
	}
  $attrs = array( );
  $args = func_get_args();
  $args = array_shift($args);
  $s = splice_attrs($attrs, $args,$start);
  $email = h($email);
  $text = se($text);
  return "<a href=\"mailto:$email\" $s>$text</a>";
}


function stylesheet($name)
{
  return "<link rel='stylesheet' href='".ROOT_VPATH."/css/$name.css'/>\n";
}

function call_to($display, $number)
{
  return link_to($display, "callto:+1".$number);
}