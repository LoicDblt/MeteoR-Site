function change_graph(temp_humi, id_bouton){
	if (id_bouton != "actif"){
		document.getElementById("actif").removeAttribute("id", "actif");
		$(document.getElementById("afficher")).fadeOut().removeAttr("id", "afficher");

		if (temp_humi == "temperature_24" || temp_humi == "humidite_24"){
			document.querySelector("button:first-child").setAttribute("id", "actif");
			$(document.querySelector("section > section:last-child > div:last-child > img:first-child")).fadeToggle().attr("id", "afficher");
		}
		else if (temp_humi == "temperature_72" || temp_humi == "humidite_72"){
			document.querySelector("button:nth-child(2)").setAttribute("id", "actif");
			$(document.querySelector("section > section:last-child > div:last-child > img:nth-child(2)")).fadeToggle().attr("id", "afficher");
		}
		else{
			document.querySelector("button:last-child").setAttribute("id", "actif");
			$(document.querySelector("section > section:last-child > div:last-child > img:last-child")).fadeToggle().attr("id", "afficher");
		}
	}
}

function affiche_min_max(){
	$(document.querySelector("header > div:last-child > div:last-child")).slideToggle(400).css("display", "flex");
}