$(function(){
	// Accordion
	$("#accordion").accordion({ header: "h3" });
	
	// Tabs
	$('#tabs').tabs();

	//hover states on the static widgets
	$('#dialog_link, ul#icons li').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);
	
});
