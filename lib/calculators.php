<?


function click_date_diff($date, $date2 = 0)
{
    if(!$date2)
        $date2 = mktime();

    $date_diff = array('seconds'  => '',
                       'minutes'  => '',
                       'hours'    => '',
                       'days'     => '',
                       'weeks'    => '',
                       
                       'tseconds' => '',
                       'tminutes' => '',
                       'thours'   => '',
                       'tdays'    => '',
                       'tdays'    => '');

    ////////////////////
    
    if($date2 > $date)
        $tmp = $date2 - $date;
    else
        $tmp = $date - $date2;

    $seconds = $tmp;

    // Relative ////////
    $date_diff['years'] = floor($tmp/(604800*52));
    $tmp -= $date_diff['years'] * (604800*52);

    $date_diff['weeks'] = floor($tmp/604800);
    $tmp -= $date_diff['weeks'] * 604800;

    $date_diff['days'] = floor($tmp/86400);
    $tmp -= $date_diff['days'] * 86400;

    $date_diff['hours'] = floor($tmp/3600);
    $tmp -= $date_diff['hours'] * 3600;

    $date_diff['minutes'] = floor($tmp/60);
    $tmp -= $date_diff['minutes'] * 60;

    $date_diff['seconds'] = $tmp;
    
    // Total ///////////
    $date_diff['tweeks'] = floor($seconds/604800);
    $date_diff['tdays'] = floor($seconds/86400);
    $date_diff['thours'] = floor($seconds/3600);
    $date_diff['tminutes'] = floor($seconds/60);
    $date_diff['tseconds'] = $seconds;

    return $date_diff;
}


function ago($dt)
{
  $now = time();
  $diff = click_date_diff(time(), $dt);

  if ($diff['years']==0)
  {
    if ($diff['weeks']==0)
    {
      if ($diff['days']==0)
      {
        if ($diff['hours']==0)
        {
          if ($diff['minutes']==0)
          {
            if ($diff['seconds']==1) return '1 second ago';
            return $diff['seconds'] . ' seconds ago';
          } else {
            if ($diff['minutes']==1) return '1 minute ago';
            return $diff['minutes'] . ' minutes ago';
          }
        } else {
          if ($diff['hours']==1) return '1 hour ago';
          return $diff['hours'] . ' hours ago';
        }
      } else {
        if ($diff['days']==1) return '1 day ago';
        return $diff['days'] . ' days ago';
      }
    } else {
      if ($diff['weeks']==1) return '1 week ago';
      return $diff['weeks'] . ' weeks ago';
    }    
  } else {
    if ($diff['years']==1) return '1 year ago';
    return $diff['years'] . ' years ago';
  }  
}

function distance($d)
{
  $feet = $d * 3.2808399;
  $diff = array();
  
  $tmp = $feet;
  
  $diff['miles'] = floor($tmp/5280);
  $tmp -= $diff['miles']*5280;
  
  $diff['tenth_miles'] = floor($tmp/52800);
  $tmp -= $diff[tenth_miles]*52800;
  
  $diff['feet'] = floor($tmp);
  
  if ($diff['miles']==0)
  {
    if ($diff['tenth_miles']==0)
    {
      return $diff['feet'] . ' feet away';
    }
    return $diff['tenth_miles'] . ' miles away';
  } else {
    return $diff['miles']+$diff['tenth_miles'] . ' miles away';
  }

}

?>