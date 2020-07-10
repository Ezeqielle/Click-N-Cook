function verif() {
	
	if(popup() == true){
		/*var xhr = new XMLHttpRequest();
		xhr.open("POST", "modifyUser.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function(){
			if(xhr.readyState==4 && xhr.status == 200){
				
			}

		}
		var verif = "verif=" + 1;
		xhr.send(verif);*/
		return true;
		
	}
	return false;
}

function popup() {
	if(confirm('Are you sure ?')) {

		return true;
	} else{
		window.location.reload();
	}
}