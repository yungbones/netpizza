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
		$(".completeorder").removeClass("disabled");
		$(".clearorders").removeClass("disabled");

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
		$(".completeorder").addClass("disabled");
		$(this).addClass("disabled");

		myOrder = [];
		totalPrice = 0;

		$("#orders").html("<li>Kosár tartalma jelenleg üres</li>");
		$("#total").html("Összesen: <b>0 Forint</b>");

		footerFix();
	});

	$(".completeorder").click(function() {
		if (!$.isEmptyObject(myOrder)) {
			$(".clearorders").addClass("disabled");
			$(this).addClass("disabled");

			$(".fullscreen").css("display", "block");

			var sended = {};

			for (var i = 0; i < myOrder.length; i++)
				sended[i] = $.parseJSON('{"name": "' + myOrder[i]["name"] + '", "type": ' + myOrder[i]["type"] + ', "price": ' + myOrder[i]["price"] + ', "count": ' + myOrder[i]["count"] + '}');
			
			$.ajax({
	            type: "POST",
	            url: "misc/order.php",
	            data: sended,

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
	                	$(".fullscreen").css("display", "none");

	                	$.notify({
	                        message: "Előbb jelentkezz be",
	                        status: "error",
	                        timeout: 2000,
	                        onClose: function() {
	                            location.reload()
	                        }
	                    });
	                }
		        }
	      	});
	        
			footerFix();

	        return false;
	    }
	});

	$(".card-header").click(function() {
		footerFix();
	})
});

function numberFormat(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function footerFix() {
    $(".page-footer").removeClass("fixed-bottom");

    if ($(".page-footer").position().top < $(window).height() - $(".page-footer").height())
        $(".page-footer").addClass("fixed-bottom");
}