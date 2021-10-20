<?php
class cron_parser
{
 
  public $cron_schedule;
 
  function __construct($cron_schedule) {
    $this->cron_schedule = $cron_schedule;
  }
 
  public function next_runtime()
  {
 
    $j=0; // used as an internal counter to prevent an endless loop
    $time = time(); // time right now
    // split our cron into variables
    list( $cron_minute, $cron_hour, $cron_day, $cron_month, $cron_day_of_week ) = explode( " ", $this->cron_schedule );
 
    if( $cron_day_of_week == "0" ) $cron_day_of_week = "7"; // 7 and 0 both mean Sunday
 
    do
    {
      // split our current time into variables (the $time variable will be updated after every iteration)
      list( $now_minute, $now_hour, $now_day, $now_month, $now_day_of_week ) = explode( " ", date("i H d n N", $time ) );
 
      if( $cron_month != "*" )
      {
        if( (int)$cron_month != $now_month )
        {
          $j++; // internal counter
          $now_month = (int)$now_month + 1; // increment the month by 1
          // set minute, hour and day to 0 so we start at the begining of the next month
          $time = mktime( 0, 0, 0, $now_month, 1, date("Y",$time) ); // $time + 1 month
          continue; // start again
        }
      }
 
      if( $cron_day != "*" )
      {
        if( (int)$cron_day != $now_day )
        {
          $j++; // internal counter
          $now_day = (int)$now_day + 1; // increment the day by 1
          // set minute and hour to 0 so we start at the begining of the next day
          $time = mktime( 0, 0, 0, $now_month, $now_day, date("Y",$time) ); // $time + 1 day
          continue; // start again
        }
      }
 
      if( $cron_hour != "*" )
      {
        if( (int)$cron_hour != $now_hour )
        {
          $j++; // internal counter
          $now_hour = (int)$now_hour + 1; // increment the hour by 1
          // set minute to 0 so we start at the begining of the next hour
          $time = mktime( $now_hour, 0, 0, $now_month, $now_day, date("Y",$time) ); // $time + 1 hour
          continue; // start again
        }
      }
 
      if( $cron_minute != "*" )
      {
        if( (int)$cron_minute != $now_minute )
        {
          $j++; // internal counter
          $now_minute = (int)$now_minute + 1; // increment the minute by 1
          $time = mktime( $now_hour, $now_minute, 0, $now_month, $now_day, date("Y",$time) ); // $time + 1 minute
          continue; // start again
        }
      }
 
      // must be checked last
      if( $cron_day_of_week != "*" )
      {
        if( (int)$cron_day_of_week != $now_day_of_week )
        {
          $j++; // internal counter
          $now_day = (int)$now_day + 1; // increment the day by 1
           // set minute and hour to 0 so we start at the begining of the next day
          $time = mktime( 0, 0, 0, $now_month, $now_day, date("Y",$time) ); // $time + 1 day
          continue; // start again
        }
      }
 
      break; /* if we reach this point, all the conditions
      * above are true and we have our next cron time!
      */
 
    } while( $j < 10000 );
 
    return $time;
 
  }
 
  public function last_runtime()
  {
 
    $j=0; // used as an internal counter to prevent an endless loop
    $time = time(); // time right now
    // split our cron into variables
    list( $cron_minute, $cron_hour, $cron_day, $cron_month, $cron_day_of_week ) = explode( " ", $this->cron_schedule );
 
    if( $cron_day_of_week == "0" ) $cron_day_of_week = "7"; // 7 and 0 both mean Sunday
 
    do
    {
      // split our current time into variables (the $time variable will be updated after every iteration)
      list( $now_minute, $now_hour, $now_day, $now_month, $now_day_of_week ) = explode( " ", date("i H d n N", $time ) );
 
      if( $cron_month != "*" )
      {
        if( (int)$cron_month != $now_month )
        {
          $j++; // internal counter
          $now_month = (int)$now_month - 1; // subtract the month by 1
          $num_days_in_month = (int)date("t",mktime( 0, 0, 0, $now_month, 1, date("Y",$time) ) ); // number of days in month
          // set minute, hour and day to their highest value so we start at the end of the previous month
          $time = mktime( 23, 59, 59, $now_month, $num_days_in_month, date("Y",$time) ); // $time - 1 month
          continue; // start again
        }
      }
 
      if( $cron_day != "*" )
      {
        if( (int)$cron_day != $now_day )
        {
          $j++; // internal counter
          $now_day = (int)$now_day - 1; // subtract the day by 1
          // set minute and hour to their highest value so we start at the end of the previous day
          $time = mktime( 23, 59, 59, $now_month, $now_day, date("Y",$time) ); // $time - 1 day
          continue; // start again
        }
      }
 
      if( $cron_hour != "*" )
      {
        if( (int)$cron_hour != $now_hour )
        {
          $j++; // internal counter
          $now_hour = (int)$now_hour - 1; // subtract the hour by 1
          // set minute and second to their highest value so we start at the end of the previous hour
          $time = mktime( $now_hour, 59, 59, $now_month, $now_day, date("Y",$time) ); // $time + 1 hour
          continue; // start again
        }
      }
 
      if( $cron_minute != "*" )
      {
        if( (int)$cron_minute != $now_minute )
        {
          $j++; // internal counter
          $now_minute = (int)$now_minute - 1; // subtract the minute by 1
          $time = mktime( $now_hour, $now_minute, 59, $now_month, $now_day, date("Y",$time) ); // $time - 1 minute
          continue; // start again
        }
      }
 
      // must be checked last
      if( $cron_day_of_week != "*" )
      {
        if( (int)$cron_day_of_week != $now_day_of_week )
        {
          $j++; // internal counter
          $now_day = (int)$now_day - 1; // subtract the day by 1
           // set minute and hour to 0 so we start at the end of the previous day
          $time = mktime( 0, 0, 0, $now_month, $now_day, date("Y",$time) ); // $time - 1 day
          continue; // start again
        }
      }
 
      break; /* if we reach this point, all the conditions
      * above are true and we have our previous cron time!
      */
 
    } while( $j < 10000 );
 
    return $time;
 
  }
 
}

function get_time_difference( $start, $end ) {
	if(!is_numeric($start)) {
	    $uts['start']      =    strtotime( $start );
	} else {
		$uts['start']		= $start;	
	}
	
	if(!is_numeric($end)) {
	    $uts['end']        =    strtotime( $end );
	} else {
		$uts['end']			=	$end;
	}
	
	if($uts['start'] > $uts['end']) {
		return array(
			'days'		=> 0,
			'hours'		=> 0,
			'minutes'	=> 0,
			'seconds'	=> 0
		);
	}
	
    if( $uts['start']!==-1 && $uts['end']!==-1 ) {
        if( $uts['end'] >= $uts['start'] ) {
            $diff    =    $uts['end'] - $uts['start'];
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        } else {
            trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
        }
    } else {
        trigger_error( "Invalid date/time data detected", E_USER_WARNING );
    }
    return( false );
}

function icron_parser($min, $hour = '*') {
	if(preg_match('/\*\/[\d]+/', $min)) {
		$min	= explode('/', $min);
		$m		= 0;
		
		while($m <= date('i')) {
			$m += $min[1];
		}
		
		$min = $m;
	}

	if(preg_match('/\*\/[\d]+/', $hour)) {
		$hour	= explode('/', $hour);
		$h		= 0;
		
		while($h <= date('H')) {
			$h += $hour[1];
		}
		
		$hour = $h;
	}
	
	if($hour == 24) {
		$hour = 0;
	}
	
	//echo $min . ' ' . $hour . ' * * *' . PHP_EOL;
	
	$c = new cron_parser($min . ' ' . $hour . ' * * *');
	return $c->next_runtime();
}

function cron_next_run($m = '*', $h = '*') {
	$now	= date('Y-m-d H:i:s');
	$next	= date('Y-m-d H:i:s', icron_parser($m, $h));

	/*$diff	= mysql_query('SELECT SQL_NO_CACHE TIMEDIFF(\'' . $next . '\', \'' . $now . '\') AS diff_t');		
	$diff	= mysql_fetch_assoc($diff);
	
	$diff_t	= explode(':', $diff['diff_t']);
	*/
	$diff_t = get_time_difference($now, $next);
	
	//echo $now . ' -> ' . $next . PHP_EOL;
	//print_r($diff_t);
	
	$ret = array(
		'h'	=> (int)$diff_t['hours']  + ((int)$diff_t['days'] * 24),
		'm'	=> $diff_t['minutes'],
		's'	=> $diff_t['seconds']
	);
	
	return $ret;
}
