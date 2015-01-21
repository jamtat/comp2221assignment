<?php

include 'session.inc';
require_once 'common.php';
successHeader();
$players = readJSON('players.json');
$scores = readJSON('scores.json');

$justids = array_map(function($s) {
	return $s[0];
}, $scores);
$justscores = array_map(function($s) {
	return $s[1];
}, $scores);
$justdates = array_map(function($s) {
	return $s[2];
}, $scores);

//Sort the scores by points then date obtained
array_multisort($justscores, SORT_DESC, $justdates, SORT_ASC, $scores);

$seen = array();
$filteredScores = array();

//Filter scores array to only show the best earliest attempt by every player
foreach($scores as $s) {
	if(in_array($s[0], $seen)) {
		//Do nothing
	} else {
		array_push($seen, $s[0]);
		array_push($filteredScores, $s);
	}
}

if(isset($_POST['id']) and isset($_POST['score'])) {
	//We're inserting a new score
	if(!isset($_POST['id'])) {
		API_DIE('No player id sent');
	}
	if(!isset($_POST['fbpic'])) {
		API_DIE('No player picture sent');
	}
	if(!isset($_POST['name'])) {
		API_DIE('No player name sent');
	}
	if(!isset($_POST['score'])) {
		API_DIE('No player score sent');
	}
	$playerId = $_POST['id'];
	$newScore = array($playerId, intval($_POST['score']), time());
	$newPlayer = array(
		"id"    => $playerId,
		"fbpic" => $_POST['fbpic'],
		"name"  => $_POST['name']
	);

	//Save the new player and score. Could use a DB for this but JSON is quick
	$players[$playerId] = $newPlayer;
	saveJSON($players, 'players.json');
	array_push($scores, $newScore);
	saveJSON($scores, 'scores.json');

	$result['success'] = 1;

} else {
	//Just getting leaderboard
	$result['leaderboard'] = array_map(function($s) use($players) {
		$player = $players[$s[0]];

		return array (
			"score"     => $s[1],
			"id"        => $s[0],
			"timestamp" => $s[2],
			"player"    => $player
		);
	}, $filteredScores);
}

API_SUCCESS();

?>
