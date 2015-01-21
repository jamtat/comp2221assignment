<?php

include 'twitterquestions.php';
include 'session.inc';
require_once 'common.php';

function map_question($question) {
	global $fname;
	$ques = Array (
		"type" => $question['type'],
		"title" => $question['title'],
		"colour" => '#'.substr(md5(json_encode($question)), -6)
	);
	switch ($question['type']) {
		case "choice":
		case "multiple":
			$ques['answers'] = $question['answers'];
			break;
		case "text":
			break;
		case "twitter":
			$tweets = getTweets($fname, $question['sortkey'], $question['count']);
			$ques['tweets'] = $tweets['tweets'];
			shuffle($ques['tweets']);
			$ques['sortkey'] = $tweets['sortkey'];
			break;
	}

	return $ques;

}

function map_answer($question) {
	switch ($question['type']) {
		case "choice":
			return $question['answers'][$question['correct']];
			break;
		case "multiple":
			return array_values(array_intersect_key($question['answers'],array_flip($question['correct'])));
			break;
		case "text":
			return $question['correct'];
		default:
			return '';
	}
}

try {
	//Get the questions
	$input = file_get_contents('questions.json');
	$questions = json_decode($input, true);
	$totalquestions = count($questions);
} catch(Exception $e) {
	API_DIE('Internal Server Error: Could not fetch questions');
}

if(isset($_GET['q']) and $_GET['q'] === 'all') {
	if(!isset($_GET['name'])) {
		API_DIE('No name specified');
	}
	$fname = $_GET['name'];
	//Shortcut to return all the questions
	$result['questions'] = array_map("map_question", $questions);

} else {
	//Otherwise try to parse an integer out of question index
	if(isset($_GET['q'])) {
		try {
			$q = $_GET['q'];
			$q = intval($q); //Defaults to 0 if it's non numeric anyway
		} catch(Exception $e) {
			API_DIE('Invalid Question Number Specified');
		}
		//Question index out of bounds!
		if($q >= $totalquestions or $q < 0) {
			API_DIE("Question number $q out of bounds. Only $totalquestions questions available. Questions are 0 indexed.");
		}
	} else {
		API_DIE('No question index specified');
	}

	if (isset($_GET['hint'])) {
		//We want the hint for a question!
		$result['hint'] = $questions[$q]['hint'];
	} elseif (isset($_GET['answer'])) {
		//Send the correct answer!
		$result['answer'] = map_answer($questions[$q]);
	} else {
		//Just return the question
		$result['question'] = map_question($questions[$q]);
	}

}

//Append some summary information
$result['summary'] = Array(
	"total_questions" => $totalquestions
);

API_SUCCESS();

?>
