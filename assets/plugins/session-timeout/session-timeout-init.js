$.sessionTimeout({
	redirUrl: '../../logout.php',
	warnAfter: 3000000,
	redirAfter: 3100000,
	onWarn: function () {
		window.location.href='../../logout.php'
	}
});