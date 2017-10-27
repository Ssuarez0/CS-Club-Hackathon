$(document).ready(function() {
	var request;

	$('#registration-form').submit(function(event){
		//Prevent default behavior of POST
		event.preventDefault();

		//Abort any pre-existing request
		if(request) {
			request.abort();
		}

		var $form = $(this);

		var $inputs = $form.find("input, select, button, textarea");

		var serializedData = $form.serialize();

		$inputs.prop("disabled", true);

		request = $.ajax({
			url: "./register.php",
			type: "POST",
			data: serializedData
		});

		request.done(function(response, textStatus, jqXHR){
			//Code for success
			var response_html = "<div id='feedback_info' class='row justify-content-center'><div class='alert alert-danger'><button class='close' data-dismiss='alert'>x</button> msg </div></div>";
			response_html = response_html.replace("msg", response);
			//if(response === )
			$("#club_logo").before(response_html);
		});

		request.fail(function(jqXHR, textStatus, errorThrown){
			//Log error to console
			console.error(
				"The following error occurred: " + textStatus, errorThrown
			);
		});

		request.always(function () {
        	$inputs.prop("disabled", false);
    	});
	});
	
});