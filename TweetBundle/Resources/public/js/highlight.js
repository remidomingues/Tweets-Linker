/**
 * Highlights the current selected text in the browser
**/
function highlight(type) {
        var selected = getSelected();

	//We can have only one selection for each type of input
	//Needs to be improved
        $("mark").each(function() {
                if ($(this).attr('type') == type) {
                        $(this).replaceWith($(this).text());
                }
        });

	// Switch on type
        var classMark = "class='";
        if (type == "type") {
                classMark += 'type_mark';
                $('#form_tweet_type_highlighted').val(selected);
        } else if (type == "location") {
                classMark += 'location_mark';
                $('#form_tweet_location_highlighted').val(selected);
        } else if (type == "time") {
                classMark += 'time_mark';
                $('#form_tweet_time_highlighted').val(selected);
        }
        classMark += "'";

	//Insert mark tags
        var src_str = $("div.article-well").html();
        var term = selected;
        term = term.replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*");
        var pattern = new RegExp("("+term+")", "i");

        src_str = src_str.replace(pattern, "<mark " + classMark + " type='" + type + "'>$1</mark>");
        src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"$1</mark>$2<mark>$4");

        $("div.article-well").html(src_str);
}

/**
 * Returns the currently selected text
**/
function getSelected() {
        var text = "";
        if (window.getSelection) {
                text = window.getSelection().toString();
        } else if (document.selection && document.selection.type != "Control") {
                text = document.selection.createRange().text;
        }
        return text;
}
