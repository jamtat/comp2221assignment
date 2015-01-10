<!doctype html>
<html>
	<head>
		<title>COMP2221 Assignment</title>
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

		<div id="progress">
			<div id="progress-inner" data-index="" data-total=""></div>
		</div>

		<script id="questions-template" type="text/x-handlebars-template">
			{{#each questions}}
			{{#with this}}
				<section class="question vcentre" data-question-id="{{@index}}" data-question-type="{{type}}">
					<div class="wrapper">
						<h3 data-question-id="{{@index}}">{{title}}</h3>
						<div class="answer-input-wrapper cf">
							{{#ifvalue type value="choice"}}
								{{#each answers}}
								<div class="answer-input-row cf">
									<input type="radio"
										name="questioninput-{{lookup ../id}}"
										id="question-input-{{lookup ../id}}-{{@index}}"
										data-answer="{{this}}">
									<label for="question-input-{{lookup ../id}}-{{@index}}">{{this}}</label>
								</div>
								{{/each}}
							{{/ifvalue}}

							{{#ifvalue type value="multiple"}}
								{{#each answers}}
								<div class="answer-input-row cf">
									<input type="checkbox"
										name="question-input-{{lookup ../id}}"
										id="question-input-{{lookup ../id}}-{{@index}}"
										data-answer="{{this}}">
									<label for="question-input-{{lookup ../id}}-{{@index}}">{{this}}</label>
								</div>
								{{/each}}
							{{/ifvalue}}

							{{#ifvalue type value="text"}}
								<input type="text"
									name="question-input-{{lookup ../id}}"
									id="question-input-{{lookup ../id}}-{{@index}}"
									placeholder="Type your answer here">
							{{/ifvalue}}
						</div>

						<div class="answer-button" data-question-id="{{@index}}">Submit Answer</div>
					</div>
				</section>
			{{/with}}
			{{/each}}
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
