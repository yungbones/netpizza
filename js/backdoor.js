$(document).ready(function() {
	$("[data-toggle='tooltip']").tooltip();

	setInterval(function() {
		/* $("#accordion").load("../misc/backdoor-load.php");

		//console.log($(".counter").html().trim() + " - " + $(".card").length);

		if ($(".counter").html().trim() < $(".card").length) {
			$(".counter").html($(".card").length);
			$.notify("Új rendelés érkezett", "warning");
		} */

		//console.log($(".card-header")[0].getAttribute("data-target").replace("#collapse-", ""));

		/*
		echo '<div class="card bg-light mb-3">
			<div class="card-header" id="order-' . $rows["oid"] . '" data-toggle="collapse" data-target="#collapse-' . $rows["oid"] . '">
				<h5 class="mb-0"><b>' . ($rows["ostatus"] < 2 ? "#" . $rows["oid"] : $icon) . '</b> - Rögzített rendelés <b>' . $rows["aname"] . '</b> névre <b>(' . $rows["aadress"] . ')</b></h5>

				' . ($rows["ostatus"] < 2 ? "<a class=\"font-small\"><i class=\"fa fa-clock\"></i> " . $rows["otime"] . "</a>" : "") . '
			</div>

			<div id="collapse-' . $rows["oid"] . '" class="collapse">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">' . $text . '</div>

						<div class="col-md-6">' . $button . '</div>
					</div>
				</div>
			</div>
		</div>';
		*/

		//console.log($(".card-header").length);

		$.ajax({
			type: "POST",
			url: "misc/backdoor-load.php",
			//dataType: "json",
			data: {id: $(".card-header")[0] ? $(".card-header")[0].getAttribute("data-target").replace("#collapse-", "") : 0},

			success: function(data) {
				//console.log(data);

				if (data && data != "fail") {
					$.notify("Új rendelés érkezett", "warning");

					$("#accordion").prepend(data);
					$(".counter").html($(".card").length);
				}
			}
		});
	}, 1000);

	$("#accordion").on("click", ".btn", function() {
		//alert($(this).attr("class").split(' '));

		if ($(this).hasClass("start-btn")) {
			var btn = $(this);
			var sended = $.parseJSON('{"func": "update", "id": ' + btn.data("holder") + ', "newvalue": 1}');

	        $.ajax({
	            type: "POST",
	            url: "misc/backdoor-update.php",
	            data: sended,

	            success: function(data) {
	                if (data == "failed")
	                	$.notify("Hiba", "error");
					else if (data == "updated")
	                	btn.html("<i class='fas fa-hourglass-end'></i> Futár megérkezett").removeClass("btn-success").addClass("btn-warning").removeClass("start-btn").addClass("finish-btn").addClass("btn-sm");
		        }
	      	});
	        
	        return false;
		}
		else if ($(this).hasClass("finish-btn")) {
			var btn = $(this);
			var sended = $.parseJSON('{"func": "update", "id": ' + btn.data("holder") + ', "newvalue": 2}');

	        $.ajax({
	            type: "POST",
	            url: "misc/backdoor-update.php",
	            data: sended,

	            success: function(data) {            	
	                if (data == "failed")
	                	$.notify("Hiba", "error");
	                else if (data == "updated") {
	                	btn.replaceWith("<button data-holder='" + sended["id"] + "' class='btn btn-sm btn-dark' disabled><i class='fas fa-check-circle'></i> Rendelés teljesítve</button>");
	                	$("#remove-" + sended["id"]).remove();

	                	var datas = $("#order-" + sended["id"] + " b");
	                	$("#order-" + sended["id"]).html("<h5 class='mb-0'><i class='far fa-check-circle text-success'></i></b> - Rögzített rendelés <b>" + datas[1].innerHTML + "</b> névre <b>" + datas[2].innerHTML + "</b></h5>");
	                }
		        }
	      	});
	        
	        return false;
		}
		else if ($(this).hasClass("delete-btn")) {
			var btn = $(this);
			var sended = $.parseJSON('{"func": "update", "id": ' + btn.data("holder") + ', "newvalue": 3}');

			$.ajax({
				type: "POST",
				url: "misc/backdoor-update.php",
				data: sended,

				success: function(data) {
					if (data == "failed")
						$.notify("Hiba", "error");
					else if (data == "updated") {
						for (var i = 0; i < $(".btn").length; i++)
							if (($(".btn")[i].classList.contains("start-btn") || $(".btn")[i].classList.contains("finish-btn")) && $(".btn")[i].getAttribute("data-holder") == sended["id"])
								$(".btn")[i].remove();

						$("#br-" + sended["id"]).remove();

						btn.replaceWith("<button data-holder='" + sended["id"] + "' class='btn btn-sm btn-dark' disabled><i class='fas fa-exclamation-circle'></i> Rendelés törölve</button>");
						
						var datas = $("#order-" + sended["id"] + " b");
						$("#order-" + sended["id"]).html("<h5 class='mb-0'><i class='far fa-times-circle text-danger'></i></b> - Rögzített rendelés <b>" + datas[1].innerHTML + "</b> névre <b>" + datas[2].innerHTML + "</b></h5>");
					}
				}
			});

			return false;
		}
	});

	$(".list-group-item").click(function() {
		if ($(this).data("holder") == "exit") {
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
	                            location.reload()
	                        }
	                    });
				}
			});

			return false;
		}
		else if ($(this).data("holder") == "print") {
			
		}
	});

	$(".btn-login").click(function() {
		$.ajax({
			type: "POST",
			url: "misc/backdoor-login.php",
			data: {password: $("#password").val()},

			success: function(data) {
				if (data == "logged")
					$.notify({
                        message: "Sikeresen bejelentkeztél",
                        status: "success",
                        timeout: 2000,
                        onClose: function() {
                            location.reload()
                        }
                    });
				else if (data == "pw")
					$.notify("Hibás jelszó", "error");
			}
		});

		return false;
	});

	$(".btn-addpayment").click(function() {
		var value = $("#value").val();
		var desc = $("#desc").val();

		if (value < 0 || isNaN(value) || !$.isNumeric(value) || value.charAt(0) == '0' || desc.length < 10)
			$.notify("Nem megfelelő bemeneti adatok", "error");
		else {
			$("#modalPayment").modal("hide");
			var sended = $.parseJSON('{"func": "insert_p", "value": ' + Math.floor(value) + ', "desc": "' + desc + '"}');

			$.ajax({
				type: "POST",
				url: "misc/backdoor-update.php",
				data: sended,

				success: function(data) {
					if (data == "success")
						$.notify("Sikeres feljegyzés", "success");
					else if (data == "error")
						$.notify("Sikertelen feljegyzés", "error");
				}
			});

			return false;
		}
	});
});