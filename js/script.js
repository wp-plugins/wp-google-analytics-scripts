

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
});
function validateuacode(str){
    return (/^ua-\d{4,9}-\d{1,4}$/i).test(str.toString());
}