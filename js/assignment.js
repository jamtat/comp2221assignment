var app = {

	init: function() {
		app._.getEl()
		app._.getQuestions(function(err,result) {
			if(err) {
				app.view.showErr(err+'<br>Try reloading the application')
			} else {
				console.log(result)
				app.model.questions = result.questions
				app.ready()
			}
		})

		// Place following code after FB.init call.

		function onLogin(response) {
			if (response.status == 'connected') {
				FB.api('/me?fields=first_name', function(data) {
					var welcomeBlock = document.getElementById('fb-welcome');
					welcomeBlock.innerHTML = 'Hello, ' + data.first_name + '!';
				});
			}
		}

		FB.getLoginStatus(function(response) {
			// Check login status on load, and if the user is
			// already logged in, go directly to the welcome message.
			if (response.status == 'connected') {
				onLogin(response);
			} else {
				// Otherwise, show Login dialog first.
				FB.login(function(response) {
					onLogin(response);
				}, {scope: 'user_friends, email'});
			}
		});
	},

	ready: function() {
		app.el.body.className += ' loaded'
		q('#intro div').addEventListener('click', function(e) {
			app.el.body.className = ''
		})
		app.view.updateProgress()
	},

	_: {
		getEl: function() {
			app.el.main = q('main')
			app.el.progressBar = I('progress-inner')
			app.el.err = I('err')
			app.el.body = document.body
		},
		getJSON: function(url, data, callback) {
			$.ajax({
				url: url,
				dataType: 'json',
				data: data,
				success: function(response) {
					callback(null, response)
				},
				error: function(xhr, ajaxOptions, err) {
					callback(err, {})
				}
			})
		},
		getQuestions: function(callback) {
			app._.getJSON('questions.php', {q:'all'}, function(err, j) {
				callback(err,j)
			})
		}
	}
	,
	el: {
		progressBar: null,
		main: null,
		body: null,
		err: null
	},

	model: {
		questions: [],
		currentQuestion: 0
	},

	view: {
		updateProgress: function() {
			app.el.progressBar.setAttribute('data-index', app.model.currentQuestion)
			app.el.progressBar.setAttribute('data-total', app.model.questions.length)
			app.el.progressBar.style.width = Math.round(app.model.currentQuestion/app.model.questions.length)+'%'
		},
		showErr: function(err) {
			app.el.err.innerHTML = err
			$('html').addClass('error')
		}
	}
}

function Q(selector) {
	return document.querySelectorAll(selector)
}

function q(selector) {
	return Q(selector)[0]
}

function I(ID) {
	return document.getElementById(ID)
}


$(function() {
	app.init.call(app)
})
