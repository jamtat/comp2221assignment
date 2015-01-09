<?php
session_name("chqx69assignment");
session_start();


function getStatus($status) {
	$httpStatus = Array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	);
	return $httpStatus[$status];
}

function successHeader() {
	$status_header = 'HTTP/1.1 200 ' . getStatus(200);
	// set the status
	header($status_header);
	//Set the content type
	$content_type = 'application/json';
	header('Content-type: ' . $content_type);
}

function API_DIE($str, $code=500) {
	$status = (isset($code))?$code:500;
	$status_header = 'HTTP/1.1 ' . $status . ' ' . getStatus($status);
	// set the status
	header($status_header);
	//Set the content type
	$content_type = 'application/json';
	header('Content-type: ' . $content_type);

	$message = array(
		'error' => $str
	);

	echo json_encode($message);
	exit();
}

$result = array();

function API_SUCCESS() {
	global $result;
	successHeader();
	echo json_encode($result);
}

try {
	//Get the questions
	$input = file_get_contents('questions.json');
	$questions = json_decode($input, true);
	$totalquestions = count($questions);
} catch(Exception $e) {
	API_DIE('Internal Server Error: Could not fetch questions');
}

if(isset($_GET['q'])) {
	try {
		$q = $_GET['q'];
		$q = intval($q); //Defaults to 0 if it's non numeric anyway
	} catch(Exception $e) {
		API_DIE('Invalid Question Number Specified');
	}
	if($q >= $totalquestions) {
		API_DIE("Question number $q out of bounds. Only $totalquestions questions available. Questions are 0 indexed.");
	}
} else {
	API_DIE('No question index specified');
}

if (isset($_GET['hint'])) {
	$result['hint'] = $questions[$q]['hint'];
} elseif (isset($_GET['answer'])) {
	$result['answer'] = $questions[$q]['answers'][$questions[$q]['correct']];
} else {
	$result['question'] = Array(
		"title" => $questions[$q]['title'],
		"answers" => $questions[$q]['answers']
	);
}

$result['summary'] = Array(
	"total_questions" => $totalquestions
);

API_SUCCESS();

?>
