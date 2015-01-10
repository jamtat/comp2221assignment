var app = {

	init: function() {
		app._.getEl()
		q('#intro span').innerHTML = 'Loading Questions'
		I('fbpic').style.backgroundImage = 'url('+app.fbPic+')'
		app._.getQuestions(function(err,result) {
			if(err) {
				app.view.showErr(err+'<br>Try reloading the application')
			} else {
				console.log(result)
				app.model.questions = result.questions.map(function(q,i){
					q.id = i
					return q
				})
				var templateSource = I('questions-template').innerHTML
				var questionsTemplate = Handlebars.compile(templateSource)
				app.el.main.innerHTML = questionsTemplate({questions:app.model.questions})
				$('.question input').change(function(e) {
					console.log(e)
				})
				app.ready()
			}
		})
	},

	START_COLOUR: '#737BCD',
	ACTIVE_QUESTION_CLASS: 'active',

	ready: function() {
		app.el.body.className += ' loaded'
		q('.begin-button').addEventListener('click', function(e) {
			app.el.body.className = ''
			app.goToQuestion(0)
		})
		app.view.updateProgress()
	},

	goToQuestion: function(questionId) {
		$('.question').removeClass(app.ACTIVE_QUESTION_CLASS)
		$('.question[data-question-id='+questionId+']').addClass(app.ACTIVE_QUESTION_CLASS)
		app.model.currentQuestion = questionId
		app.view.updateProgress()
		app.view.updateBackgroundHue()
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
		},
		RGBToHSL: function(RGB) {
			var R = RGB.R/255,
				G = RGB.G/255,
				B = RGB.B/255,
				Cmax = Math.max(R,G,B),
				Cmin = Math.min(R,G,B),
				D = Cmax - Cmin,
				L = (Cmax+Cmin)/2,
				S = D==0 ? 0 : D/(1-Math.abs(2*L-1)),
				H = 60 * (
					Cmax == R ? ((G-B)/D)%6 : (
						Cmax == G ? (B-R)/D+2 : (R-G)/D+4
					)
				)

			return {H:isNaN(H)?0:H,S:S,L:L}
		},
		hexToHSL: function(hex) {
			return app._.RGBToHSL(app._.hexToRGB(hex))
		},
		hexToRGB: function(hex) {
			hex = hex[0]==='#' ? hex.substr(1) : hex.toLowerCase() //Remove preceeding #
			hex = hex.length===3 ? hex.split('').join('0')+0 : hex //Expand 3 colour hex
			var R = parseInt(hex.substr(0,2), 16),
				G = parseInt(hex.substr(2,2), 16),
				B = parseInt(hex.substr(4,2), 16)
			return {R:R,G:G,B:B}
		},
		RGBToHex: function(RGB) {
			return '#'+[RGB.R,RGB.G,RGB.B].map(function(n) {
				var h = n.toString(16)
				return h.length < 2 ? '0'+h : h
			}).join('')
		},
		HSLToRGB: function(HSL) {
			var H = HSL.H,
				S = HSL.S,
				L = HSL.L,

			H = H < 0 ? 360+(H%360) : H%360

			var C = (1-Math.abs(2*L-1))*S,
				X = C*(1-Math.abs((H/60)%2-1)),
				m = L-C/2,
				R,G,B

			switch(true) {
				case H <= 60:
					R=C
					G=X
					B=0
					break;
				case H <= 120:
					R=X
					G=C
					B=0
					break;
				case H <= 180:
					R=0
					G=C
					B=X
					break;
				case H <= 240:
					R=0
					G=X
					B=C
					break;
				case H <= 300:
					R=X
					G=0
					B=C
					break;
				default:
					R=C
					G=0
					B=X
					break;
			}
			R = Math.round((R+m)*255)
			G = Math.round((G+m)*255)
			B = Math.round((B+m)*255)
			return {R:R,G:G,B:B}
		},
		HSLToHex: function(HSL) {
			return app._.RGBToHex(
				app._.HSLToRGB(HSL)
			)
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
			app.el.progressBar.setAttribute('data-index', app.model.currentQuestion+1)
			app.el.progressBar.setAttribute('data-total', app.model.questions.length)
			app.el.progressBar.style.width = Math.round(100*(app.model.currentQuestion+1)/app.model.questions.length)+'%'
		},
		showErr: function(err) {
			app.el.err.innerHTML = err
			$('html').addClass('error')
		},
		updateBackgroundHue: function() {
			var hue = app._.hexToHSL(app.model.questions[app.model.currentQuestion].colour).H,
				HSL = app._.hexToHSL(app.START_COLOUR)
			HSL.H = hue
			console.log(hue, HSL)
			var newColour = app._.HSLToHex(HSL)
			console.log(newColour)
			document.documentElement.style.backgroundColor = newColour
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
