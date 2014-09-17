jQuery(document).ready(function(){
	var y= jQuery("#scripts-type-selector").val();
if(y==1)
			 {
         jQuery( "#ftr-trackid-input" ).parent().parent().hide();
         jQuery( "#ftr-scripts-input" ).parent().parent().show();	
      	 	}
			 	else if(y==2)
			 	{
			 	 jQuery( "#ftr-trackid-input" ).parent().parent().show();
       		 jQuery( "#ftr-scripts-input" ).parent().parent().hide();		
			 		
			 		}
			 		else if((y==0) || (y=='')) {
			 		  jQuery( "#ftr-trackid-input" ).parent().parent().hide();
      			  jQuery( "#ftr-scripts-input" ).parent().parent().hide();		
			 			}
	jQuery('form.myform').submit(function(){
		var y= jQuery("#scripts-type-selector").val();
if(y==1)
			 {
         jQuery( "#ftr-trackid-input" ).parent().parent().hide();
         jQuery( "#ftr-scripts-input" ).parent().parent().show();	
      	 	}
			 	else if(y==2)
			 	{
			 	 jQuery( "#ftr-trackid-input" ).parent().parent().show();
       		 jQuery( "#ftr-scripts-input" ).parent().parent().hide();		
			 		
			 		}
			 		else {
			 		  jQuery( "#ftr-trackid-input" ).parent().parent().hide();
      			  jQuery( "#ftr-scripts-input" ).parent().parent().hide();		
			 			}
		var x=jQuery('#ftr-trackid-input').val();
		x=validateuacode(x);
		if(x)
		{
			return true;
		}
		else
		{
			alert('Please enter the correct Google Analytics UA Tracking ID');
			jQuery('#ftr-trackid-input').addClass('error_wpga');			
			return false;			
			}
		
		});
		
		jQuery("#scripts-type-selector").change(function(){
			var y= jQuery("#scripts-type-selector").val();
			 if(y==1)
			 {
        jQuery( "#ftr-trackid-input" ).parent().parent().hide();
        jQuery( "#ftr-scripts-input" ).parent().parent().show();	
          }
			 	else if(y==2)
			 	{
			    jQuery( "#ftr-trackid-input" ).parent().parent().show();
             jQuery( "#ftr-scripts-input" ).parent().parent().hide();		
			 		
			 		}
			 		else {
			 			 jQuery( "#ftr-trackid-input" ).parent().parent().hide();
       				 jQuery( "#ftr-scripts-input" ).parent().parent().hide();		
			 			}
			 	
		
		});
		
		jQuery("#submit").click(function(){
			var a=jQuery( "#ftr-trackid-input" ).val();
			var b=jQuery( "#ftr-scripts-input" ).val();
			var y= jQuery("#scripts-type-selector").val();
			if(y==1)
			 {
         jQuery( "#ftr-trackid-input" ).parent().parent().hide();
         jQuery( "#ftr-scripts-input" ).parent().parent().show();	
      	 	}
			 	else if(y==2)
			 	{
			 	 jQuery( "#ftr-trackid-input" ).parent().parent().show();
       		 jQuery( "#ftr-scripts-input" ).parent().parent().hide();		
			 		
			 		}
			 		else {
			 		  jQuery( "#ftr-trackid-input" ).parent().parent().hide();
      			  jQuery( "#ftr-scripts-input" ).parent().parent().hide();		
			 			}
				
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

