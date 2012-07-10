$(function(){
	$("form.tabs-form").find("select").change(function(){
		$("form.tabs-form").submit();
	});
});