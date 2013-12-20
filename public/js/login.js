$(document).ready(function() { 
	
	$('form.forgot').hide();

	$('.forgot-opener').click(function(event) {
		
		$('form').not('.forgot').hide();
		$('.forgot').fadeIn().find('input:first').focus();
		return false;
	});

	$('.forgot-closer').click(function(event) {
		
		$('.forgot').hide();
		$('form').not('.forgot').fadeIn().find('input:first').focus();
		return false;
	});
});