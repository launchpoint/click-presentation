<?

function replace_in_file($path, $replace)
{
  $s = file_get_contents($path);
  foreach($replace as $k=>$v)
  {
    $s = str_replace($k, $v, $s);
  }
  file_put_contents($path, $s);
  return $s;
  
}


function scriptify($s, $quote="\"")
{
	$s = str_replace("\n", "\\n", $s);
	$s = str_replace($quote, "\\$quote", $s);
	return $quote . $s . $quote;
}

function is_blank($v)
{
  return !$v || trim($v)=='' || $v==0;
}

function nonblank($v)
{
  if (is_blank($v)) return '';
  return $v;
}


function click_percent_format($n)
{
	return number_format(round($n*100,2),2) . "%";
}

function splice_date($parts)
{
	return strtotime("${parts['month']}/${parts['day']}/${parts['year']}");
}

function simple_format($s, $hyperlink=true)
{
  $s = htmlentities($s);
  $s = preg_replace("/\n/", "<br/>", $s);
  $s = preg_replace("/\s\s/", " &nbsp;", $s);
  $s = preg_replace("/\t/", " &nbsp;", $s);
  if($hyperlink) $s = preg_replace('@(https?://([-\w\.:\@]+)+(:\d+)?(/([\w/_\.\)\(,\-]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
  return $s;
}

function excerpt($s, $length=30)
{
  $s = strip_tags($s);
  if(strlen($s)>$length) $s = substr($s, 0, $length)."...";
  return $s;
}

function click_phone_format($phone)
{
  $phone = preg_replace("/[^0-9]/", "", $phone);
  if(strlen($phone) == 7)
    return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
  elseif(strlen($phone) == 10)
    return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
  else
    return $phone;
}


function currency_format($f, $suppress_blanks = false)
{
  if($suppress_blanks && is_blank($f)) return '';
  if ($f>=0)
  {
    return '$'.number_format($f,2);
  }
  return '($'.number_format(abs($f),2).')';
}


function assemble_attrs($attrs, $args, $param_count=1)
{
  for($i=$param_count;$i<count($args);$i+=2)
  {
    $k=$args[$i];
    $v=$args[$i+1];
    if ($k == 'class' && array_key_exists($k, $attrs))
    {
      $v = ' '.$v;
    } else {
      $attrs[$k]='';
    }
    $attrs[$k].=$v;
  }
  return $attrs;
}

function splice_attrs($attrs, $args, $param_count=1)
{
	$attrs = assemble_attrs($attrs, $args, $param_count);
	return to_xml_attributes($attrs);
}

function to_xml_attributes($attrs)
{
  $s = array();
  foreach($attrs as $k=>$v)
  {
    $v = htmlentities($v);
    $s[] = "$k = \"$v\"";
  }
  $s = join($s,' ');
  return $s;
}

function extract_date($arr)
{
  return strtotime($arr['month'] . '/' . $arr['day'] . '/' . $arr['year']);
}

function u($s)
{
  return urlencode($s);
}

function h($s)
{
  return htmlentities($s);
}

function j($s)
{
  $s = preg_replace("/'/", "\\'", $s);
  $s = preg_replace("/\r/", "", $s);
  $s = preg_replace("/\n/", "\\n", $s);
  return $s;
}