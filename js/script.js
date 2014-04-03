

jQuery(document).ready(function(){
	jQuery('form').submit(function(){
		
		var x=jQuery('#footer-trackid-input').val();
		x=validateuacode(x);
		if(x)
		{
			return true;
		}
		else
		{
			alert('Please enter the correct Google Analytics UA Tracking ID');
			jQuery('#footer-trackid-input').addClass('error_wpga');			
			return false;			
			}
		
		});
		
		jQuery("#scripts-selector").change(function(){
			var y= jQuery("#scripts-selector").val();
			 if(y==1)
			 {
        jQuery( "#footer-trackid-input" ).parent().parent().fadeOut("slow");
        jQuery( "#footer-scripts-input" ).parent().parent().fadeIn("slow");				 	
			 	
			 	
			 	}
			 	else
			 	if(y==2)
			 	{
			 	 jQuery( "#footer-scripts-input" ).parent().parent().fadeOut("slow");	
			 	  jQuery( "#footer-trackid-input" ).parent().parent().fadeIn("slow");		
			 		
			 		}
			 	
		
		});
		
		jQuery("#submit").click(function(){
			var a=jQuery( "#footer-trackid-input" ).val();
			var b=jQuery( "#footer-scripts-input" ).val();
			var y= jQuery("#scripts-selector").val();
			if(a=='' && b=='')
			{
			alert('Please fill one input box');
			return false;
}
else
if((a!='' || b!='') && y=='0')
{
alert('Please choose one value in  the select box');
return false;	
	}
else
return true;			
			
		});
		
});


		


function validateuacode(str){
	if(str=='')
	return true;
	else 
    return (/^ua-\d{4,9}-\d{1,4}$/i).test(str.toString());
}
