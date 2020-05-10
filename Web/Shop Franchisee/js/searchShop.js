let timerSearch;

$(document).ready(function() {
	//setTimeout(function() {
	//on enregistre toutes les données entrées par l'utilisateur

	$('#searchShop').keyup(function() {
		$('#result').html('');
		const product = $(this).val();

		if(product != "") {
			if(timerSearch) {
				clearTimeout(timerSearch);
			}
			timerSearch = setTimeout(function() {
			//on envoie les données à la page PHP
			$.ajax({
				type: 'POST',
				url: '../extensions/searchEngineShop.php',
				data: 'product=' + encodeURIComponent(product),
				success : function(data) {
					//si la fonction à bien été éxécuté on écris les infos sur la page sinon on affiche un message d'erreur
					if(data != "") {
						$('#result').append(data);
					} else {
						document.getElementById('result').innerHTML = 
						"<article class='col-lg-12' style='background-color: white;'><div style='font-size: 20px; text-align: center;'><strong>Try something else !</strong></div></article>"
					}
				}
			});
			}, 500);
		} else {
			if(timerSearch) {
				clearTimeout(timerSearch);
			}
			timerSearch = setTimeout(function() {
			//on envoie les données à la page PHP
			$.ajax({
				type: 'POST',
				url: '../extensions/searchEngineShop.php',
				data: 'reset',
				success : function(data) {
					//si la fonction à bien été éxécuté on écris les infos sur la page sinon on affiche un message d'erreur
					if(data != "") {
						$('#result').append(data);
					} else {
						document.getElementById('result').innerHTML = 
						"<article class='col-lg-12' style='background-color: white;'><div style='font-size: 20px; text-align: center;'><strong>Try something else !</strong></div></article>"
					}
				}
			});
			}, 500);
		}
	});
	//	}, 5000);
});  