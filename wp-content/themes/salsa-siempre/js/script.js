(function () {
	/* timetable filters */
	var filters = document.querySelector(".timetable-filters");
	var classes = document.querySelectorAll(".timetable-class");
	var length = classes.length;

	if (filters && classes) {
		filters.addEventListener("change", function (e) {
			var type = e.target.dataset.type;
			if (type) {
				for (var i = 0; i < length; i+=1) {
					classes[i].style.opacity = (classes[i].dataset.type === type) ? "1" : "0.2" ;
				}
			} else {
				for (var i = 0; i < length; i+=1) {
					classes[i].style.opacity = "1";
				}
			}
		});
	}

	/* mobile navigation menu */
	var menu = document.querySelector( '.menu' );
	var toggle = document.querySelector( '.menu-toggle' );
	if (menu && toggle) {
		toggle.onclick = function (e) {
			e.stopPropagation();
			if (-1 !== menu.className.indexOf("active")) {
				menu.className = menu.className.replace(" active", "" );
			} else {
				menu.className += " active";
			}
		};

		document.onclick = function () {
			if (-1 !== menu.className.indexOf("active")) {
				menu.className = menu.className.replace("active", "");
			}
		};
	}
})();
