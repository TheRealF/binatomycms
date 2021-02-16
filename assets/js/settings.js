//Gestione del messaggio di conferma per eliminare il blog
$(document).ready(function (){
	var customAlert = new CustomAlert();
	const href = window.location.href;
	let tempblogname = new URL(href).pathname.split("/");
	let currblogname = tempblogname.pop() || tempblogname.pop();
	const finalurl = "blog\/" + currblogname +"\/elimina";
	$("#formDelete").attr("action", finalurl);
	$('#hidebtn').show();
	$('#hidebtn').on('click', function() {
		$('#hidebtn').hide();
		customAlert.alert('Sei sicuro?','Attenzione!', finalurl);
		$("#confirmButton").on("click", function (){
			$("#formDelete").submit();
		});
		$("#abortButton").on("click", function (){
			location.reload();
		});
	});
});
