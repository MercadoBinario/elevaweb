function elvaweb_validate_url(url) {
	return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}
(function($) {
	$("body").on("click", ".btn_dlt", function() {
		var id = $(this).data('id');
		var action = $(this).data('action');
		var r = confirm("Are You Sure Want to delete ?");
		if (r == true) {
			window.location.replace(elevaweb.admin_url + '?page=eleva-post-config&action=delete_post&id=' + id);
		}
	});
	$("body").on("change", "#src_feed", function() {
		var data = {
			"action": "getFeedcatajax",
			"feed": $(this).val()
		};
		$.ajax({
			type: 'POST',
			url: elevaweb.ajax_url,
			data: data,
			beforeSend: function() {
				$('#loading').show();
			},
			complete: function() {
				$('#loading').hide();
			},
			success: function(response) {
				if (response) {
					$("#src_cat").html(response);
				}
			}
		});
	});
	$("body").on("click", "#save_schedule", function() {
		$("#save_type").val("save_schedule");
		$("#eleva-new-post").submit();
	});
	$("body").on("click", "#save_schedule_published", function() {
		$("#save_type").val("save_schedule_published");
		$("#eleva-new-post").submit();
	});
	$("#eleva-new-post").submit(function() {
		var validatedURL = true;
		if ($('#src_feed option:selected').val() == "") {
			$('#src_feed').focus();
			$('#src_feed').siblings('.validation-error').addClass('active');
			return false;
		} else {
			$('#src_feed').siblings('.validation-error').removeClass('active');
		}
		if ($('#src_cat option:selected').val() == "") {
			$('#src_cat').focus();
			$('#src_cat').siblings('.validation-error').addClass('active');
			return false;
		} else {
			$('#src_cat').siblings('.validation-error').removeClass('active');
		}
		if ($(this).find('#add_image').is(':checked')) {
			var urls = $('textarea[name="eleva_image_url"]').val();
			if (urls == "") {
				$("#new_post_error").html('<div class="error fade"><p>Enter url for image</p></div>');
				return false;
			}
			if (urls != "" && typeof urls != "undefined") {
				var matches = urls.match(/\n/g);
				var new_lines = matches ? matches.length : 0;
				if (new_lines) {
					urls = urls.split('\n');
				} else if (urls.indexOf(',') > -1) {
					urls = urls.split(',');
				} else {
					urls = [urls];
				}
				if (urls.length > 0) {
					urls.forEach(function(value, index) {
						validatedURL = elvaweb_validate_url(value);
						if (!validatedURL) {
							return false;
						}
					});
				}
				if (!validatedURL) {
					$("#new_post_error").html('<div class="error fade"><p>Invalid url given for image</p></div>');
					return false;
				}
			}
		}
		if ($('input[name="days[]"]:checked').length == 0) {
			$('input[name="days[]"]').parents().closest('.eleva-profile-input').find('.validation-error').addClass('active');
			return false;
		} else {
			$('input[name="days[]"]').parents().closest('.eleva-profile-input').find('.validation-error').removeClass('active');
		}
		if ($('select[name="schedule_hour"] option:selected').val() == "-1" || $('select[name="schedule_minutes"] option:selected').val() == "-1") {
			$('select[name="schedule_hour"]').parents().closest('.eleva-radio-button-time').find('.validation-error').addClass('active');
			return false;
		} else {
			$('select[name="schedule_hour"]').parents().closest('.eleva-radio-button-time').find('.validation-error').removeClass('active');
		}
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: elevaweb.ajax_url,
			data: data,
			beforeSend: function() {
				$('#loading').show();
			},
			complete: function() {
				$('#loading').hide();
			},
			success: function(response) {
				var obj = $.parseJSON(response);
				if (obj.Error == 0) {
					$("#new_post_error").html('<div class="error fade"><p>' + obj.Msg + '</p></div>');
					location.reload();
				} else if (obj.Error == 1) {
					$("#new_post_error").html('<div class="error fade"><p>' + obj.Msg + '</p></div>');
				}
			}
		});
		return false;
	});
	$("#forgot_password").submit(function() {
		var email_id = $("#email_id").val();
		if (email_id == "") {
			$(".massage").html("Enter Email");
			return false;
		}
		var data = {
			"action": "reset_password_user",
			"email_id": email_id
		};
		$.post(elevaweb.ajax_url, data, function(response) {
			var obj = JSON.parse(response);
			if (obj.success == 1) {
				$("#email_id").val('');
				$(".massage").html(obj.message);
			} else {
				$("#email_id").val('');
				$(".massage").html(obj.message);
			}
		});
		return false;
	});
})(jQuery);