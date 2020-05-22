$(document).ready(function(){
	$('#buy').click(function(){
		const products = document.getElementsByName('products');
		var productsArray = [];

		//on boucle pour chercher tous les produits que l'utilisateur a choisi.
		for(let i = 0; i < products.length; i++){
			const productsSelect = products[i].lastChild;
			const choice = productsSelect.selectedIndex;
			if(productsSelect.options[choice].value > 0){
				productsArray.push(productsSelect.name);
				productsArray.push(productsSelect.options[choice].value);
			}
		}

		//on transforme le tableau en chaîne de caractère pour pouvoir l'envoyer en POST.
		productsArray = JSON.stringify(productsArray);

		//on fait la requête seulement si le tableau est rempli c'est à dire supérieur à deux caractères -> [].
		if(productsArray.length > 2){

			$.ajax({
				type : 'POST',
				url : "http://localhost/Click-N-Cook/web/Shop%20Franchisee/extensions/insertOrderr.php",
				data : 'productsArray='  + productsArray,

				success: function(output) {
					alert(output);
				},
				error: function(request, status, error){
					console.log(request, status, error);
				}
			});

		//on met un setTimeout car la requête ajax doit se faire avec nom de domaine et elle prend légèrement plus de temps qu'avec l'adresse IP.
		/*setTimeout(function(){
			window.location.replace('shopPayment.php');
		}, 500);*/
		}
	});
});