(function () {
	/* splash */
	var splash = document.querySelector(".splash");
	var dismiss = document.querySelector(".splash-dismiss");

	if (splash && dismiss) {
		dismiss.onclick = function (e) {
			e.stopPropagation();
			splash.style.display = "none";
			sessionStorage.splash = true;
		};
	}

	/* timetable filters */
	var content = document.querySelector(".content");
	var filters = document.querySelector(".timetable-filters");
	var classes = document.querySelectorAll(".timetable-class");
	var length = classes.length;
	var days = document.querySelectorAll(".timetable-day");

	if (filters) {
		var type = filters.querySelector(":checked").dataset.type;
		if (type) {
			showClasses(type);
		}

		filters.addEventListener("change", function (e) {
			var type = e.target.dataset.type;
			if (type) {
				showClasses(type);
			} else {
				showAllClasses();
			}
		});
	}

	function showClasses(type) {
		var i;
		content.className = "content _filtered"
		for (i = 0; i < length; i+=1) {
			classes[i].className = "timetable-class" + (classes[i].dataset.type === type ? "" : " _hidden");
		}
		for (i = 0; i < 7; i+=1) {
			days[i].className = "timetable-day" + (days[i].querySelector(".timetable-class:not(._hidden)") ? "" : " _hidden");
		}
	}

	function showAllClasses() {
		var i;
		content.className = "content"
		for (i = 0; i < length; i+=1) {
			classes[i].className = "timetable-class";
		}
		for (i = 0; i < 7; i+=1) {
			days[i].className = "timetable-day";
		}
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
