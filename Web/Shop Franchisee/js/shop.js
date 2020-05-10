$(document).ready(function(){
	$('#buy').click(function(){
		const products = document.getElementsByName('products');
		let productsValue;
		let productsArray = [];

		//on boucle pour chercher tous les produits que l'utilisateur a choisi.
		for(let i = 0; i < products.length; i++){
			const productsSelect = products[i].lastChild;
			console.log(productsSelect);
			const choice = productsSelect.selectedIndex;
			console.log(productsSelect.selectedIndex);
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
				url : 'shopPayment.php',
				data : 'productsArray=' + productsArray,
				timeout : 3000
			});

			//on met un setTimeout car la requête ajax doit se faire avec nom de domaine et elle prend légèrement plus de temps qu'avec l'adresse IP.
			setTimeout(function(){
				window.location.replace('shopPayment.php');
			}, 500);
		}
	});
});