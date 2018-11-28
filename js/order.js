$(document).ready(function() {
    $("[data-toggle='tooltip']").tooltip();

    footerFix();
    $(window).on("resize", function() {
        footerFix();
    });

	var myOrder = [];
	var totalPrice = 0;

	var types = ["30 cm", "50 cm"];
	var colors = ["bg-warning", "bg-danger"];

	$(".btn-add").click(function() {
		self = [];
		self["name"] = $(this).data("holder");
		self["type"] = $(this).data("type");
		self["price"] = $(this).data("price");
		self["count"] = 1;

		totalPrice += self["price"];

		var result = myOrder.find(x => x["name"] == self["name"] && x["type"] == self["type"]);
		if (result) {
			result["count"]++;
			result["price"] += self["price"];

			$("#orders").empty();

			for (var i = 0; i < myOrder.length; i++) {
				var counted = myOrder[i]["count"] > 1 ? " (" + myOrder[i]["count"] + "x)" : "";
				$("#orders").html($("#orders").html() + "<li class='mt-1 ml-0 pl-0'><span class='mr-2 badge badge-pill " + colors[myOrder[i]["type"] - 1] + "'>" + types[myOrder[i]["type"] - 1] + "</span>" + counted + " <b>" + myOrder[i]["name"] + "</b> - " + numberFormat(myOrder[i]["price"]) + " Ft</li>");
			}
		}
		else {
			myOrder.push(self);

			if (myOrder.length == 1)
				$("#orders").empty();

			$("#orders").html($("#orders").html() + "<li class='mt-1 ml-0 pl-0'><span class='mr-2 badge badge-pill " + colors[self["type"] - 1] + "'>" + types[self["type"] - 1] + "</span> <b>" + self["name"] + "</b> - " + numberFormat(self["price"]) + " Ft</li>");
		}
		
		$("#total").html("Összesen: <b>" + numberFormat(totalPrice) + " Forint</b>");

		footerFix();
	});

	$(".clearorders").click(function() {
		myOrder = [];
		totalPrice = 0;

		$("#orders").html("<li>Kosár tartalma jelenleg üres</li>");
		$("#total").html("Összesen: <b>0 Forint</b>");

		footerFix();
	});

	$(".completeorder").click(function() {
		if (!$.isEmptyObject(myOrder)) {
			$(".fullscreen").css("display", "block");

			var sended = {};

			for (var i = 0; i < myOrder.length; i++)
				sended[i] = $.parseJSON('{"name": "' + myOrder[i]["name"] + '", "type": ' + myOrder[i]["type"] + ', "price": ' + myOrder[i]["price"] + ', "count": ' + myOrder[i]["count"] + '}');

			var id = $.parseJSON(getCookie("json_userdata"))["id"];
			
			$.ajax({
	            type: "POST",
	            url: "misc/order.php",
	            data: {user: id, order: sended},

	            success: function(data) {
	                if (data == "success") {
	                	myOrder = [];
						totalPrice = 0;

						$("#orders").html("<li>Kosár tartalma jelenleg üres</li>");
						$("#total").html("Összesen: <b>0 Forint</b>");

	                	$.notify("Rendelésedet sikeresen rögzítettük", "success");

	                	$(".fullscreen").css("display", "none");
	                }
	                else if (data == "fail") {
	                	$.notify("Hiba a feldolgozás során", "error");

	                	$(".fullscreen").css("display", "none");
	                }
		        }
	      	});
	        
			footerFix();

	        return false;
	    }
	});
});

function getCookie(cname) {
    var name = cname + "=";
    var ca = decodeURIComponent(document.cookie).split(';');
    
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);

        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }

    return "";
}

const numberFormat = (x) => {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function footerFix() {
    $(".page-footer").removeClass("fixed-bottom");

    var screenH = $(window).height();
    var footerH = $(".page-footer").height();
    var footerY = $(".page-footer").position().top;

    if (footerY < screenH - footerH)
        $(".page-footer").addClass("fixed-bottom");
}