var app = {

	init: function() {
		app._.getEl()
		q('#intro span').innerHTML = 'Loading Questions'
		app._.getQuestions(function(err,result) {
			if(err) {
				app.view.showErr(err+'<br>Try reloading the application')
			} else {
				console.log(result)
				app.model.questions = result.questions
				app.ready()
			}
		})
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
