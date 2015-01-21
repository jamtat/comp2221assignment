<!doctype html>
<html>
	<head>
		<meta charset=UTF-8>
		<title>COMP2221 Assignment</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/assignment.css">
	</head>
	<body class="loading">
		<?php include 'fbscript.inc' ?>
		<div id="err"></div>
		<div id="fbpic"></div>
		<div id="intro" class="vcentre">
			<div class="wrapper">
				<h2 id="fb-welcome"></h2>
				<h1>Ben's Amazing Quiz</h1>
				<span>Checking with Facebook</span>
				<div class="begin-button">Get Started</div>
			</div>
		</div>
		<main>

		</main>

		<div id="summary" class="vcentre">
			<div class="wrapper">
				<h1>You Scored</h1>
				<div id="score-display">
					<div id="total-score"></div>
					<div id="total-questions"></div>
				</div>

				<ol id="answers-list" class="cf"></ol>
				<ol id="leaderboard" class="cf"></ol>
			</div>
		</div>

		<div id="progress">
			<div id="progress-inner" data-index="" data-total=""></div>
		</div>

		<script id="questions-template" type="text/x-handlebars-template">
			{{#questions}}
				<section class="question " data-question-id="{{@index}}" data-question-type="{{type}}">
					<div class="wrapper">
						<h3>{{title}}</h3>
						<div class="answer-input-wrapper cf">
							{{#ifvalue type value="choice"}}
								<span>Choose one</span>
								{{#answers}}
								<div class="answer-input-row cf">
									<input type="radio"
										name="questioninput-{{@../index}}"
										id="question-input-{{@../index}}-{{@index}}"
										value="{{this}}">
									<label for="question-input-{{@../index}}-{{@index}}">{{this}}</label>
								</div>
								{{/answers}}
							{{/ifvalue}}

							{{#ifvalue type value="multiple"}}
								<span>Choose one or many</span>
								{{#answers}}
								<div class="answer-input-row cf">
									<input type="checkbox"
										name="question-input-{{@../index}}"
										id="question-input-{{@../index}}-{{@index}}"
										value="{{this}}">
									<label for="question-input-{{@../index}}-{{@index}}">{{this}}</label>
								</div>
								{{/answers}}
							{{/ifvalue}}

							{{#ifvalue type value="text"}}
								<input type="text"
									name="question-input-{{@../index}}"
									id="question-input-{{@../index}}-{{@index}}"
									placeholder="Type your answer here">
							{{/ifvalue}}

							{{#ifvalue type value="twitter"}}
								{{#tweets}}
								<div class="answer-input-row cf tweet-question">
									<input type="radio"
									name="questioninput-{{@../index}}"
									id="question-input-{{@../index}}-{{@index}}"
									value="{{@index}}">
									<label for="question-input-{{@../index}}-{{@index}}">
										<div class="tweet-text" style="border-color:#{{this.colour}};">&ldquo;{{this.text}}&bdquo;</div>
									</label>
									<div class="tweet-meta" style="background-image:url({{this.display_pic}});">
										<a href="{{this.url}}" target="_blank">- {{this.username}}</a><span></span>
									</div>
								</div>
								{{/tweets}}
							{{/ifvalue}}
						</div>

						<div class="hint-button" id="hint-button-{{@index}}" data-question-id="{{@index}}" style="color:{{colour}};">Get Hint</div>

						<div class="answer-button" data-question-id="{{@index}}">Submit Answer</div>

					</div>
				</section>
			{{/questions}}
		</script>

		<script id="answers-template" type="text/x-handlebars-template">
			{{#answers}}
				<li {{#if this}}class="correct"{{/if}}></li>
			{{/answers}}
		</script>

		<script id="leaderboard-template" type="text/x-handlebars-template">
			{{#leaderboard}}
				<li><img src="{{this.player.fbpic}}">{{this.score}} - {{this.player.name}}</li>
			{{/leaderboard}}
		</script>


		<script src="js/handlebars-v2.0.0.js"></script>
		<script>
		Handlebars.registerHelper('ifvalue', function (conditional, options) {
			if (options.hash.value === conditional) {
				return options.fn(this)
			} else {
				return options.inverse(this);
			}
		});
		</script>
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/assignment.js"></script>
	</body>
</html>
