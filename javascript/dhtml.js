
// Instanciation et initialisation de l'objet xmlhttprequest
function file(fichier) {

	// Instanciation de l'objet pour Mozilla, Konqueror, Opera, Safari, etc ...
	if (window.XMLHttpRequest) {

		xhr_object = new XMLHttpRequest ();

	// ... ou pour IE
	} else if (window.ActiveXObject) {

		xhr_object = new ActiveXObject ("Microsoft.XMLHTTP");

	} else {

		return (false);

	}

	xhr_object.open ("GET", fichier, false);
	xhr_object.send (null);

	if (xhr_object.readyState == 4) {

		return (xhr_object.responseText);

	} else {

		return (false);

	}

}


// Affichage des donnees aTexte dans le bloc identifie par aId
function afficheDonnees(aId, aTexte) {

	document.getElementById(aId).innerHTML = aTexte;

}


// aCible : id du bloc de destination; aCode : argument a passer a la page php chargee du traitement et de l'affichage
function verifResultat(aCible, aCode, iLimit) {
	if (aCode != '' && aCode.length >= iLimit) {

		if (texte = file('facturation_dhtml.php?code='+escape(aCode))) {

			afficheDonnees (aCible, texte);

		} else

			afficheDonnees (aCible, '');

	}

}


// Change dynamiquement la classe de l'element ayant l'id aIdElement pour aClasse
function setStyle(aIdElement, aClasse) {

	aIdElement.className = aClasse;

}

function get_MyClient(){


$.ajax(
{
  url : 'getDataUser.php',
  type: "POST",
  data : {idP: '10'}
})

  .done(function(data) {
    $("#respuesta").html(data);
  })

  .fail(function(data) {
    alert( "error" );
  })

  .always(function(data) {
    alert( "complete" );
  });

}

