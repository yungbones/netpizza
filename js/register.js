$(document).ready(function() {
    $("#registerbtn").click(function() {        
    	if ($("#name").val().length <= 3)
    		$.notify("Név legalább 3 karakter kell legyen", "error");
    	else if ($("#phone").val().length != 11)
    		$.notify("Hibás telefonszám asd", "error");
    	else if ($("#password").val().length < 8)
    		$.notify("Jelszó legalább 8 karakter kell legyen", "error");
    	else if ($("#password").val() != $("#password-confirmation").val())
    		$.notify("A két Jelszó nem egyezik", "error");
        else
            submitForm();
    });

    function submitForm() {
        var data = $("#register-form").serialize();

        $.ajax({
            type: "POST",
            url: "misc/register.php",
            data: data,

            success: function(data) {
	            if (data == "phone")
	            	$.notify("A telefonszám már regisztrálva van.", "error");
                else if (data == "fail")
                    $.notify("Hiba történt. Nézz vissza később!", "error");
	            else if (data == "registered")
	                $.notify({
                        message: "Sikeresen regisztráltál.",
                        status: "success",
                        onClose: function() {
                            location.href = "index.php"
                        }
                    });
	        }
      	});
        
        return false;
    }
});