<?php

require_once 'twitteroauth/autoloader.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'ripmFgeM35APE3fjBL8KwcafL');
define('CONSUMER_SECRET', 'Gc5rr1vNgJoCi1or7TMbI4QnOasqrKAsxvrwL4lw97EoKjVvAF');
define('ACCESS_TOKEN', '25868427-4EYvTyjWcQtCrPjHRefKhHDiIC5hRl0313mzxMEKK');
define('ACCESS_TOKEN_SECRET', 'O1SM4xLWvoFceY5KUmRVvCTkCA1cVOwojdffL7tIkZmAa');

function doSearch($query)
{
	$toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	return $toa->get('search/tweets', array(
		"q" => $query
	));
}

$results = doSearch("Benjamin");

?>
<pre>
<?php

foreach ($results->statuses as $result) {
	//echo $result->user->screen_name . ": " . $result->text . "\n";
	print_r((array)$result);
}

?>
</pre>
