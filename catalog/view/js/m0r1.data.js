$(document).ready(function()
{
	var token = "7459b7c0d03886592cef947f07b40999c037c218",
	$street = $("div.delivery-ways input[name='street']"),
	$house = $("div.delivery-ways input[name='house']"),
	type = "ADDRESS";

	$street.suggestions({
  		token: token,
  		type: type,
  		hint : false,
  		bounds: "street",
  		constraints: {
  			"label"	 : "",
  			"locations" : {
  				"region" : "Ханты-Мансийский Автономный округ - Югра",
  				"city"	 : "Ханты-Мансийск"
  			},
  			"deletable"	 : true
  		},
  		restrict_value : true
	});

	$house.suggestions({
  		token: token,
  		type: type,
  		hint: false,
  		bounds: "house",
  		constraints: $street,
  		restrict_value : true,
  		formatSelected : function(sug)
  		{
  			return sug.value;
  		}
	});
});