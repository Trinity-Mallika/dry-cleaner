function checkinputmaster(id) {
	//alert(id)
	var n = id.split(",");
	var msg = 0;
	for (var i = 0; i < n.length; i++) {
		//alert(n[i])
		var idname = n[i].split(" ");
		var name = document.getElementById(idname[0]).value.trim();
		var datatype = idname[1];

		if (name == "") {
			//alert("Fill Mandatory Field");
			document.getElementById(idname[0]).value = "";
			document.getElementById(idname[0]).focus();

			//alert(name);
			//document.getElementById(idname[0]).style.borderColor = "red";
			//jQuery(idname[0]).append("this text was appended");
			//document.getElementById(idname[0]).style.color = "red";
			inputNode = document.getElementById(idname[0]);
			var spanTag = document.createElement("span");
			spanTag.style.color = "red";
			spanTag.style.fontSize = "small";
			spanTag.innerHTML = " Mandatory field ";
			spanTag.id = "err_" + idname[0];

			if (!document.body.contains(document.getElementById(spanTag.id)))
				inputNode.parentNode.insertBefore(spanTag, inputNode.nextSibling);

			//var br = document.createElement("br");
			//spanTag.parentNode.insertBefore(br, spanTag);
			//spanTag.appendAfter(br);
			return false;
			msg = 1;
			break;
		}
		else
			// {
			// 	alert(idname[i]);
			// 	var errorid = "err_" + idname[0];
			// 	document.getElementById(errorid).remove();
			// }

			if (datatype == "al") {
				if (!onlyalphabets(name)) {
					alert("Please Enter only Alphabet");
					document.getElementById(idname[0]).value = "";
					document.getElementById(idname[0]).focus();
					msg = 1;
					break;
				}
			}
			else if (datatype == "an") {
				if (!alphanumeric(name)) {
					alert("Please Enter only Alpha Numeric Value");
					document.getElementById(idname[0]).value = "";
					document.getElementById(idname[0]).focus();
					msg = 1;
					break;
				}
			}
			else if (datatype == "nu") {
				if (!numeric(name)) {
					alert("Please Enter only Numbers");
					document.getElementById(idname[0]).value = "";
					document.getElementById(idname[0]).focus();
					msg = 1;
					break;
				}
			}
			else if (datatype == "dt") {
				if (!parseDate(name)) {
					alert("Please Enter only Date");
					document.getElementById(idname[0]).value = "";
					document.getElementById(idname[0]).focus();
					msg = 1;
					break;
				}
			}
	}
	//alert(msg)
	if (msg == 1) {
		//alert("false")
		return false;
	}
	else {
		//alert("ok")
		// showimg();
		return true;
	}
}