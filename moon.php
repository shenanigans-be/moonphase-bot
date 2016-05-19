<?php

require_once 'vendor/autoload.php';

$stars = ['⭐️','🌟','⭐️','🌟'];
$moon = ['🌑','🌒','🌓','🌔','🌕','🌖','🌗','🌘'];

$timestamp = time();
$moonstance = $moon[moon_phase(date('Y', $timestamp), date('n', $timestamp), date('j', $timestamp))];

$lines = 3;
$positions = 10;

$hasMoon = false;
// randomly determine where the moon is
// @todo make this accurate?
$moonPosition = round(rand(0, ($lines * ($positions - 1))));

$tweet = '';

for($l = 0; $l < $lines; $l++) {
	for($i = 0; $i < $positions; $i++) {
		echo ($l * $positions) + $i;
		if(($l * $positions) + $i == $moonPosition) {
			echo 'hello moon';
			$tweet .= $moonstance;
		}
		else  $tweet .= getStar();
	}
	
	$tweet .= "\n";
}

function getStar() {
	global $stars;
	// have 50/50 shot of being a star
	$u = round(rand(0, ((count($stars) * 4) - 1)));
	if(isset($stars[$u])) return $stars[$u];
	else return '   ';
}


require 'settings.php';

$twitter = new TwitterAPIExchange($settings);
$twitter->buildOauth('https://api.twitter.com/1.1/statuses/update.json', 'POST')
    ->setPostfields(['status' => $tweet])
    ->performRequest();

// echo it for testing
echo $tweet;

/**
 * THIS IS MAGIC I GOT FROM THE INTERNET
 */
function moon_phase($year, $month, $day) {
	$c = $e = $jd = $b = 0;

	if ($month < 3)
	{
		$year--;

		$month += 12;
	}

	++$month;

	$c = 365.25 * $year;

	$e = 30.6 * $month;

	$jd = $c + $e + $day - 694039.09;	//jd is total days elapsed

	$jd /= 29.5305882;					//divide by the moon cycle

	$b = (int) $jd;						//int(jd) -> b, take integer part of jd

	$jd -= $b;							//subtract integer part to leave fractional part of original jd

	$b = round($jd * 8);				//scale fraction from 0-8 and round

	if ($b >= 8 )

	{

		$b = 0;//0 and 8 are the same so turn 8 into 0

	}

	return $b;
}