(function()
{
	var i, element,
			month = document.getElementById("bmonth");

	monthMap = {1 : 31,
							3 : 31,
							4 : 30,
							5 : 31,
						  6 : 30,
						  7 : 31,
						  8 : 31,
						  9 : 30,
						 10 : 31,
						 11 : 30,
						 12 : 31
						 };


	for (var i = new Date().getFullYear(); i>1920; i--) {
		element = document.createElement("OPTION");
		element.className = "option";
		element.value = i;
		element.innerHTML = i;

		document.getElementById("byear").appendChild(element);
		}

	for(i=1; i<13; i++)
	{
		element = document.createElement("OPTION");
		element.className = "option";
		element.value = i;
		element.innerHTML = i;

		document.getElementById("bmonth").appendChild(element);
	}
	
	bmonth.onchange = function() {
		var i,
				lastDayInMonth = (month.value === '2')?(!(document.getElementById("byear").value%4)?29:28):monthMap[month.value];

		document.getElementById("bday").innerHTML = "<option selected class='option'>Select day</option>";

		for (i=1; i<=lastDayInMonth; i++) {
			element = document.createElement("OPTION");
			element.className = "option";
			element.value = i;
			element.innerHTML = i;

			document.getElementById("bday").appendChild(element);
		}
	}
})();