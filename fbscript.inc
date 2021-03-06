<script>
window.fbAsyncInit = function() {
	FB.init({
		appId      : '774218012661475',
		xfbml      : true,
		version    : 'v2.2'
	});

	// ADD ADDITIONAL FACEBOOK CODE HERE

	function onLogin(response) {
		if (response.status == 'connected') {
			FB.api('/me', function(data) {
				FB.api('/me/picture', function(picData) {
					var welcomeBlock = document.getElementById('fb-welcome');
					welcomeBlock.innerHTML = 'Hello, ' + data.first_name + '!';
					console.log('fetched fb data')
					console.log(data)
					app.fbData = data
					app.fbPic = picData.data.url
					app.init.call(app)
				})
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
};

(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
