$(document).ready(function() {
    $("[data-toggle='tooltip']").tooltip();

    footerFix();
    $(window).on("resize", function() {
        footerFix();
    });

	$("#login-form").validate({
        submitHandler: submitForm
    });

    $("#register-form").validate({
    	submitHandler: submitRegister
    });

    $(".btn-changepassword").click(function() {
    	var current = $("#password-current").val();
    	var new1 = $("#password-new").val();
    	var new2 = $("#password-new2").val();

    	if (new1.length < 8)
    		$.notify("Jelszó legalább 8 karakter kell legyen", "error");
    	else if (new1 != new2)
    		$.notify("A két jelszó nem egyezik", "error");
    	else {
    		$.ajax({
    			type: "POST",
    			url: "misc/change.php",
    			data: {
    				type: "password",
    				current: current,
    				new: new1
    			},

    			success: function(data) {
    				if (data == "updated") {
    					$.notify("Sikeresen megváltoztattad a jelszavad", "success");

                        $("#modalPassword").modal("hide");
                    }
    				else if (data == "old")
    					$.notify("A megadott jelszó nem a mostani jelszavad", "error");
    			}
    		});

    		return false;
    	}
    });

    $(".btn-changeaddress").click(function() {
        var new1 = $("#newaddress").val();

        $.ajax({
            type: "POST",
            url: "misc/change.php",
            data: {
                type: "address",
                new: new1
            },

            success: function(data) {
                if (data == "updated") {
                    $.notify("Sikeresen megváltoztattad a lakcímed", "success");

                    $("#myaddress").html('<i class="fas fa-map-marker-alt text-primary pr-3"></i> ' + new1);
                    $("#modalAddress").modal("hide");
                }
            }
        });

        return false;
    });

    $(".btn-logout").click(function() {        
        $.ajax({
            type: "POST",
            url: "misc/logout.php",
            data: "log out",

            success: function(data) {
                if (data == "success")
                    $.notify({
                        message: "Sikeresen kijelentkeztél",
                        status: "success",
                        timeout: 2000,
                        onClose: function() {
                            location.href = "index.php"
                        }
                    });
            }
        });

        return false;
    });
});

function submitForm() {
    var data = $("#login-form").serialize();

    $.ajax({
        type: "POST",
        url: "misc/login.php",
        data: data,

        success: function(data) {
            if (data == "captcha")
                $.notify("Az azonosítás nem sikerült (captcha error)", "warning");
            else if (data == "phone")
            	$.notify("Hibás telefonszám", "error");
            else if (data == "pw")
            	$.notify("Hibás telefonszám és jelszó kombináció", "error");
            else if (data == "logged")
            	$.notify({
                    message: "Sikeresen bejelentkeztél",
                    status: "success",
                    timeout: 2000,
                    onClose: function() {
                        location.reload()
                    }
                });
        }
  	});
    
    return false;
}

function submitRegister() {
	if ($("#reg-name").val().length <= 3)
		$.notify("Név legalább 3 karakter kell legyen", "error");
	else if ($("#reg-phone").val().length != 11 || isNaN($("#reg-phone").val()))
		$.notify("Hibás telefonszám", "error");
	else if ($("#reg-password").val().length < 8)
		$.notify("Jelszó legalább 8 karakter kell legyen", "error");
	else if ($("#reg-password").val() != $("#reg-password2").val())
		$.notify("A két Jelszó nem egyezik", "error");
    else {
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
}

function changeTo(state) {
	$("#login-form").css("display", "none");
	$("#register-form").css("display", "none");

	$("#" + state + "-form").css("display", "block");

    footerFix();
}

function footerFix() {
    $(".page-footer").removeClass("fixed-bottom");

    var screenH = $(window).height();
    var footerH = $(".page-footer").height();
    var footerY = $(".page-footer").position().top;

    if (footerY < screenH - footerH)
        $(".page-footer").addClass("fixed-bottom");
}