var app = {

	init: function() {
		app._.getEl()
		app.view.updateProgress()
	},

	ready: function() {
		
	},

	_: {
		getEl: function() {
			app.el.main = q('main')
			app.el.progressBar = I('progress-inner')
		}
	}
	,
	el: {
		progressBar: null,
		main: null
	},
	view: {
		updateProgress: function() {
			app.el.progressBar.setAttribute('data-index', app.model.currentQuestion)
			app.el.progressBar.setAttribute('data-total', app.model.questions.length)
			app.el.progressBar.style.width = Math.round(app.model.currentQuestion/app.model.questions.length)+'%'
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
