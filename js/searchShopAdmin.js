let timerSearchAdmin;

$(document).ready(function() {
	//setTimeout(function() {
	//on enregistre toutes les données entrées par l'utilisateur

	$('#searchShopAdmin').keyup(function() {
		$('#result').html('');
		const product = $(this).val();

		if(product != "") {
			if(timerSearchAdmin) {
				clearTimeout(timerSearchAdmin);
			}
			timerSearchAdmin = setTimeout(function() {
			//on envoie les données à la page PHP
			$.ajax({
				type: 'POST',
				url: '/extensions/searchEngineShopAdmin.php',
				data: 'product=' + encodeURIComponent(product),
				success : function(data) {
					//si la fonction à bien été éxécuté on écris les infos sur la page sinon on affiche un message d'erreur
					if(data != "") {
						$('#result').append(data);
					} else {
						document.getElementById('result').innerHTML = 
						"<article class='col-lg-12' style='background-color: white;'><div style='font-size: 20px; text-align: center;'><strong>" + TXT_JS_ERROR1 +  "</strong></div></article>"
					}
				}
			});
			}, 500);
		} else {
			if(timerSearchAdmin) {
				clearTimeout(timerSearchAdmin);
			}
			timerSearchAdmin = setTimeout(function() {
			//on envoie les données à la page PHP
			$.ajax({
				type: 'POST',
				url: '/extensions/searchEngineShopAdmin.php',
				data: 'reset',
				success : function(data) {
					//si la fonction à bien été éxécuté on écris les infos sur la page sinon on affiche un message d'erreur
					if(data != "") {
						$('#result').append(data);
					} else {
						document.getElementById('result').innerHTML = 
						"<article class='col-lg-12' style='background-color: white;'><div style='font-size: 20px; text-align: center;'><strong>" + TXT_JS_ERROR1 +  "</strong></div></article>"
					}
				}
			});
			}, 500);
		}
	});
	//	}, 5000);
});  