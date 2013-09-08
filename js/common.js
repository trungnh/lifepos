function get_dimensions() 
{
	var dims = {width:0,height:0};
	
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    dims.width = window.innerWidth;
    dims.height = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    dims.width = document.documentElement.clientWidth;
    dims.height = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    dims.width = document.body.clientWidth;
    dims.height = document.body.clientHeight;
  }
  
  return dims;
}

function set_feedback(text, classname, keep_displayed)
{
	if(text!='')
	{
		$('#feedback_bar').removeClass();
		$('#feedback_bar').addClass(classname);
		$('#feedback_bar').text(text);
		$('#feedback_bar').slideDown(250);
		var text_length = text.length;
		var text_lengthx = text_length*50;

		if(!keep_displayed)
		{
			$('#feedback_bar').show();
			
			setTimeout(function()
			{
				$('#feedback_bar').slideUp(250, function()
				{
					$('#feedback_bar').removeClass();
				});
			},text_lengthx);
		}
	}
	else
	{
		$('#feedback_bar').hide();
	}
}


$(document).keydown(function(event) 
{
	if (event.keyCode == 113)
	{
		window.location = SITE_URL + "/sales";
	}
});
