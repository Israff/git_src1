$(document).ready(function()
{
	$("div.delivery-ways input[name='street']").suggestions({
		token: "7459b7c0d03886592cef947f07b40999c037c218",
		type: "ADDRESS",
		count: 5,
		onSelect: function(suggestion) {
			console.log( suggestion );
		}
	});
});