//Si l'utilisateur veut payer la facture.
function buy() {
	$('#buy').click(function(e) {
		e.preventDefault();
		$.ajax({
			url : "../extensions/shopBuy.php",
			data : 'requestForm',
			success : function(data) {
				const elem = $('#bill');

				elem.empty();
				elem.append(data);
				cancel();
				submitPayment();
			}
		});
	});
}

//Si l'utilisateur annule le paiement on affiche à nouveau la facture.
function cancel() {
	$('#cancel').click(function() {
		$.ajax({
			url : "../extensions/shopBuy.php",
			data : 'cancel',
			success : function(data) {
				const elem = $('#bill');

				elem.empty();
				elem.append(data);
				buy();
				submitPayment();
			}
		});
	});
}

//une fonction pour vérifier si les champs sont bien remplis.
function check(elem, id, isNb) {
	if(elem != "" && !isNaN(elem) === isNb) {
		$(id).css({
			'border-color' : '#CCC',
		});
		return true;
	}
	$(id).css({
		'border-color' : 'red',
	});
	return false;
}

//Si l'utilisateur valide son paiment.
function submitPayment() {
	$('#submitPayment').click(function(e) {
		e.preventDefault();
		const cardNb = check($('#cardNb').val(), '#cardNb', true);
		const expiryDate = check($('#expiryDate').val(), '#expiryDate', false);
		const securityCode = check($('#securityCode').val(), '#securityCode', true);
		const address = check($('#address').val(), '#address', false);
		const checkLength = $('#securityCode').val().length != 3 ? check('', '#securityCode', false) : true;

		if(cardNb && expiryDate && securityCode && address && checkLength) {
			$.ajax({
				url : "../extensions/shopBuy.php",
				data : 'submitPayment',
				success : function(data) {
					console.log(data);
					const elem = $('#bill');
					window.location.replace("../franchisee/shop.php?payment=success");
				}
			});
		}
	});
}
$(document).ready(function() {
	buy();
	submitPayment();
});