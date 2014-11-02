(function () {
	var splash = document.querySelector( '.splash' );
	var dismiss = document.querySelector( '.splash-dismiss' );
	if (!splash || !dismiss) return;

	dismiss.onclick = function (e) {
		e.stopPropagation();
		splash.style.display = "none";
		sessionStorage.splash = true;
	};
})();
