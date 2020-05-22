$(document).ready(function(){
	$('#buy').click(function(){
		const products = document.getElementsByName('products');
		let productsArray = [];
		let productsArrays = [];

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
		//productsArrays = JSON.stringify(productsArray);

		//on fait la requête seulement si le tableau est rempli c'est à dire supérieur à deux caractères -> [].
		if(productsArrays.length > 2){

			$.ajax({
				type : 'POST',
				url : "http://localhost/click\'Ncook/franchisee/shopPayment.php",
				data : 'productsArrays=' + productsArrays,
				dataType: 'json',
				/*cache: false,
				contentType: false,
				processData: false,*/

				success : function(data){
					if(data != "") {
						console.log('cou');
					} else {
						console.log('couu');
					}
				}
			});

			//on met un setTimeout car la requête ajax doit se faire avec nom de domaine et elle prend légèrement plus de temps qu'avec l'adresse IP.
			setTimeout(function(){
				window.location.replace("shopPayment.php");
			}, 500);
		}
	});
});