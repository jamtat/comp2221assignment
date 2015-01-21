<?php

require_once 'twitteroauth/autoloader.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'ripmFgeM35APE3fjBL8KwcafL');
define('CONSUMER_SECRET', 'Gc5rr1vNgJoCi1or7TMbI4QnOasqrKAsxvrwL4lw97EoKjVvAF');
define('ACCESS_TOKEN', '25868427-4EYvTyjWcQtCrPjHRefKhHDiIC5hRl0313mzxMEKK');
define('ACCESS_TOKEN_SECRET', 'O1SM4xLWvoFceY5KUmRVvCTkCA1cVOwojdffL7tIkZmAa');


function getTweets($searchQuery, $sortKey = 'retweets', $tweetCount = 4) {
	$toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	$results = $toa->get('search/tweets', array(
		"q" => $searchQuery,
		"lang" => "en",
		"result_type" => "popular"
	));

	//file_put_contents('twitterData.txt', print_r($results, true));

	$tweets = (array)$results->statuses;

	$tweets = array_map(function($tweet) {
		return array(
			"username" => $tweet->user->screen_name,
			"text" => $tweet->text,
			"url" => 'https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id,
			"retweets" => $tweet->retweet_count,
			"favourites" => $tweet->favorite_count,
			"display_pic" => str_replace('http:', '', $tweet->user->profile_image_url),
			"colour" => $tweet->user->profile_link_color,
			"created" => $tweet->created_at
		);
	}, $tweets);


	usort($tweets, function($a, $b) use ($sortKey) {
		return $b[$sortKey] - $a[$sortKey];
	});

	$tweets = array_slice($tweets, 0, $tweetCount);


	return array(
		"tweets" => $tweets,
		"searchquery" => $searchQuery,
		"sortkey" => $sortKey,
		"count" => $tweetCount
	);
}

//Run test if this is the main file
$includes = get_included_files();
if (basename($includes[0]) == basename(__FILE__)) {
	$content_type = 'application/json';
	header('Content-type: ' . $content_type);
	echo json_encode(getTweets(isset($_GET['searchquery'])?$_GET['searchquery']:'bacon',isset($_GET['sortkey'])?$_GET['sortkey']:'retweets', 10), 128);
}

?>
