//Si l'utilisateur veut payer la facture.
function buy() {
    $('#buy').click(function(e) {
        e.preventDefault();
        $.ajax({
            url : "/extensions/shopBuyClient.php",
            data : 'requestForm',
            success : function(data) {
                const elem = $('#bill');

                elem.empty();
                elem.append(data);
                cancel();
            }
        });
    });
}

//Si l'utilisateur annule le paiement on affiche à nouveau la facture.
function cancel() {
    $('#cancel').click(function() {
        $.ajax({
            url : "/extensions/shopBuyClient.php",
            data : 'cancel',
            success : function(data) {
                const elem = $('#bill');

                elem.empty();
                elem.append(data);
                buy();
            }
        });
    });
}

$(document).ready(function() {
    buy();
});