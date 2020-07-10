$(document).ready(function() {
	$('#download').click(function() {
		$.ajax({
			url : 'http://localhost/click\'Ncook/extensions/export.php',
			success : function() {
				window.location.replace('exports/productsData.csv');
			}
		});
	})
});