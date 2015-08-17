var Login = function () {


	return {

		//main function to initiate the module
		init: function () {

			$('#forget-password').click(function () {
				$('#loginform').css('display', 'none');
				$('#forgotform').css('display', 'block');
			});

			$("#loginform").submit(function() {
				var email = $("#input-username");
				var password = $("#input-password");

				if(email.val() == "") {
					email.focus();
					return false;
				}
				if(password.val() == "") {
					email.focus();
					return false;
				}
				$(this).submit();
			});


		}

	};

}();