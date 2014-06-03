/*
 * Executed when moving to the validation div
*/

function toValidation(button) {
        $('#form_tweet_linked').val("");
        var linkedString = "";
        var irrelevantString = "";
        $(".tweet").each(function() {
                if ($(this).attr('linked') == "true") {
                        if (linkedString != "") {
                                linkedString += ","
                        }
                        linkedString += $(this).attr('tweetid');
                }
                else if ($(this).attr('linked') == "false") {
                        if (irrelevantString != "") {
                                irrelevantString += ","
                        }
                        irrelevantString += $(this).attr('tweetid');
                }
        });
        $('#form_tweet_linked').val(linkedString);
        $('#form_tweet_irrelevant').val(irrelevantString);
	$('#form_tweet_action').val('validateTweet');
	$('#form_tweet_id').val($('.main_tweet').attr("tweetid"));
        $('.div_link').hide(300);
        $('#tweets_container').hide(300);
        $('.div_tagged').show(300);
}

/*
 * Executed when moving to the search div
*/
function toSearch(button) {
	var splited_date1 = $("input[id^='datepicker_3_input_']").val().split("/");
	var splited_date2 = $("input[id^='datepicker_4_input_']").val().split("/");
	var splited_hour1 = $("input[id^='timepicker_3_']").val().split(":");
	var splited_hour2 = $("input[id^='timepicker_4_']").val().split(":");
	var date1 = new Date(splited_date1[2], splited_date1[1], splited_date1[0], splited_hour1[0], splited_hour1[1], 0);
	var date2 = new Date(splited_date2[2], splited_date2[1], splited_date2[0], splited_hour2[0], splited_hour2[1], 0);

	if (date1.getTime() <= date2.getTime()) {
		$('#form_tweet_action_search').val('getTweets');
		$('#form_tweet_id_search').val($('.main_tweet').attr("tweetid"));
        	$('#form_tweet_start_date_search').val($("input[id^='datepicker_3_input_']").val());
	        $('#form_tweet_end_date_search').val($("input[id^='datepicker_4_input_']").val());
        	$('#form_tweet_start_time_search').val($("input[id^='timepicker_3']").val());
	        $('#form_tweet_end_time_search').val($("input[id^='timepicker_4']").val());
        	$('#form_tweet_hashtag_search').val($(".input_hashtag").val());
        	$('#form_tweet_at_search').val($(".input_at").val());
        	$('#form_tweet_content_search').val($(".input_content").val());
		if ($("input[id^=input_use_timeframe_search]").prop('checked')) {
        		$('#form_tweet_use_timeframe_search').val(1);
		} else {
        		$('#form_tweet_use_timeframe_search').val(0);
		}
		$('#searchForm').submit();
		$('.popover-btn2').popover('destroy');
	} else {
		$('.popover-btn2').popover({title:"Be careful !",content:"The first date can not be set later than the second date !"});
	}

}

/*
 * Moves the timepicker popup at the good place in the page
*/
function offsetPopup(input) {
        var offset = $('#' + input.id).offset();
        $('.timepicker-popup').css('left', offset.left);
        $('.timepicker-popup').css('top', offset.top + $('#'+input.id).height() + 10);
        $('.timepicker-popup').css('position', 'fixed');
}

/*
* Executed when the user does not want to tag a given tweet
*/
function doNotWant () {
	$('#form_tweet_action').val('changeTweet');
	$('#form_tweet_id').val($('.main_tweet').attr("tweetid"));
	$('#tagForm').submit();
}

/*
* Simulate a click on a button
*/
function simulateClick(input) {
        $('#' + input.id).click();
}

function hideTimePopup() {
        $('.timepicker-popup').hide();
}

/*
* Executed when the user wants to link a tweet
*/

function linkMe(button) {
        $(button).parent().parent(".tweet_div").css("backgroundColor","#3DD12C");
        $(button).parent(".tweet").attr("linked","true");
	$(button).hide();
	$(button).siblings('.btn_undiscard').hide();
	$(button).siblings('.btn_discard').show();
	$(button).siblings('.btn_red').show();
}

/*
* Executed when the user wants to unlink a tweet
*/
function unLinkMe(button) {
        $(button).parent().parent(".tweet_div").css("backgroundColor","#ffffff");
        $(button).parent(".tweet").attr("linked","");
	$(button).hide();
	$(button).siblings('.btn_green').show();
}

/*
* Activated the controls to enable drawing on map
*/
function drawOnMap() {
        ZONE_LAYER.removeAllFeatures();
        DRAW_CONTROL.activate();
}
/*
* Disables drawing controls
*/
function stopDrawing() {
        DRAW_CONTROL.deactivate();
}

/*
* Executed when switching to relevance div
*/
function toRelevant(button) {
        $('.div_type').hide(300);
        $('.div_relevant').show(300);
}

/*
* Executed when switching to time tagging div
*/
function toTime(button) {
        $('#form_tweet_location').val($("input[id^='input_location']").val());
        $('.div_location').hide(300);
        $('.div_link').hide(300);
        if (ZONE_LAYER.features.length == 1) {
                var circle = ZONE_LAYER.features[0];
                var bounds = getHumanPosition(circle.geometry.bounds.clone());
                var center_lat = bounds.bottom + (bounds.top - bounds.bottom)/2;
                var center_lon = bounds.left + (bounds.right - bounds.left)/2;
                var radius = coordsToMeters(bounds.bottom, 0, bounds.top,0);
                $('#form_tweet_location_lat').val(center_lat);
                $('#form_tweet_location_lon').val(center_lon);
                $('#form_tweet_location_radius').val(radius);
        }
        $('#tweets_container').hide(300);
	if (parseInt($('.olControlZoom').css('right')) - $('#tweets_container').width() > 0) {
		$('.olControlZoom').css('right', parseInt($('.olControlZoom').css('right')) - $('#tweets_container').width() + 'px');
	}
        $('.div_time').show(300);
}

/*
* Executed when switching to location tagging div
*/
function toLocation(button) {
	$('#form_tweet_type').val($("select[id^='input_type']").find(":selected").attr('id'));
        $('.div_type').hide(300);
        $('.div_time').hide(300);
        $('.div_location').show(300);
}

/*
* Executed when switching to type tagging div
*/
function toType(button) {
        $('#form_tweet_relevant').val(1);
        $('.notification').hide();
        $('.div_location').hide(300);
        $('.div_relevant').hide(300);
        $('.div_type').show(300);
}

/*
* Executed when switching to linking tagging div
*/
function toLink(button) {
	var splited_date1 = $("input[id^='datepicker_1_input_']").val().split("/");
	var splited_date2 = $("input[id^='datepicker_2_input_']").val().split("/");
	var splited_hour1 = $("input[id^='timepicker_1_']").val().split(":");
	var splited_hour2 = $("input[id^='timepicker_2_']").val().split(":");
	var date1 = new Date(splited_date1[2], splited_date1[1], splited_date1[0], splited_hour1[0], splited_hour1[1], 0);
	var date2 = new Date(splited_date2[2], splited_date2[1], splited_date2[0], splited_hour2[0], splited_hour2[1], 0);

	if (date1.getTime() <= date2.getTime()) {
        	$('#form_tweet_date_begin').val($("input[id^='datepicker_1_input_']").val());
        	$('#form_tweet_date_end').val($("input[id^='datepicker_2_input_']").val());
        	$('#form_tweet_time_begin').val($("input[id^='timepicker_1_']").val());
        	$('#form_tweet_time_end').val($("input[id^='timepicker_2_']").val());
		if ($("input[id^='start_datetime_use']").prop('checked')) {
        		$('#form_tweet_start_datetime_use').val(0);
		} else {
        		$('#form_tweet_start_datetime_use').val(1);
		}
		if ($("input[id^='end_datetime_use']").prop('checked')) {
        		$('#form_tweet_end_datetime_use').val(0);
		} else {
        		$('#form_tweet_end_datetime_use').val(1);
		}
        	$('.div_time').hide(300);
        	$('.div_link').show(300);
		$('.olControlZoom').css('right', parseInt($('.olControlZoom').css('right')) + $('#tweets_container').width() + 'px');
        	$('#tweets_container').show(300);
		$('.popover-btn1').popover('destroy');
	} else {
		$('.popover-btn1').popover({title:'Be careful !', content:'The first date can not be set later than the second date !'});
	}
}

/*
* Executed when the user considers that the tweet is not relevant
*/
function notRelevant(button) {
	$('#form_tweet_action').val('validateTweet');
        $('#form_tweet_relevant').val(0);
	$('#form_tweet_id').val($('.main_tweet').attr("tweetid"));
	$('#tagForm').submit();
}

/*
* shows calendar
*/
function showCalendar(input) {
	$('#'+input.id).siblings('span').click();
}

/*
* Toggle time filter on tweets
*/
function toggleFilter() {
	$('#timefilter_div').toggle(300);	
}

/*
* Discards the tweet linked to the button passed as input
*/
function discardMe(button) {
        $(button).parent().parent(".tweet_div").css("backgroundColor","#E11010");
        $(button).parent(".tweet").attr("linked","false");
	$(button).hide();
	$(button).siblings('.btn_red').hide();
	$(button).siblings('.btn_undiscard').show();
	$(button).siblings('.btn_green').show();
}

/*
* undiscards the tweet linked to the button passed as input
*/
function unDiscardMe(button) {
        $(button).parent().parent(".tweet_div").css("backgroundColor","#ffffff");
        $(button).parent(".tweet").attr("linked","");
	$(button).hide();
	$(button).siblings('.btn_discard').show();
}
