///
/// AJAXP standard functions
///
var serialize = function(obj)
{
	var str = [];
	for(var p in obj)
	{
		str.push(p + "=" + encodeURIComponent(obj[p]));
	}
	return str.join("&");
}

// Helper to load the xml in a ajaxp style
// This keeps the function in memory even after the ajax has completed, which is a bit wasteful.
var ajax_counter=0;
var ajax_callbacks=[];
var do_ajax = function(callback, path, data)
{
	if( typeof game_name != 'undefined' )
	{
		data.game = game_name;
	}
	data.path = path;
	ajax_callbacks[ajax_counter] = callback;
	data.callback = "ajax_callbacks["+ajax_counter+"]";
	ajax_counter = ajax_counter + 1;
	var script = document.createElement('script');
	script.setAttribute('src', xhr_address+"?"+serialize(data) );

	// load the script
	document.getElementsByTagName('head')[0].appendChild(script);
}

