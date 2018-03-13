$(document).ready(
	function()
	{
		$("fieldset.line input[name='date']").datetimepicker({
			'format'			: 'DD.MM.YYYY',
			'locale'			: 'ru',
			'language'			: 'ru',
			'allowInputToggle'	: true,
			'inline'			: false
		});
	}
);