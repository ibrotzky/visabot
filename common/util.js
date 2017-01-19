function cloneObject(obj) {
	return JSON.parse(JSON.stringify(obj));
}

/**
 * formatNumber(number, dl, wl, sd, dd)
 * 
 * @param integer dl: length of decimal
 * @param integer wl: length of whole part
 * @param mixed   sd: sections delimiter
 * @param mixed   dd: decimal delimiter
 */
function formatNumber(number, dl, wl, sd, dd) {
	var re = '\\d(?=(\\d{' + (wl || 3) + '})+' + (dl > 0 ? '\\D' : '$') + ')',
		num = number.toFixed(Math.max(0, ~~dl));

	return (dd ? num.replace('.', dd) : num).replace(new RegExp(re, 'g'), '$&' + (sd || ','));
};

function parseBoolean(bool) {
	if (bool === undefined || bool === null)
	{
		return false;
	}
	else
	{
		if (typeof bool !== 'string')
		{
			bool = bool.toString();
		}

		if (isNaN(bool))
		{
			switch (bool.toLowerCase())
			{
				case "true":
					return true;
				case "false":
					return false;
				default:
					throw new Error("parseBoolean: Cannot convert string '" + bool + "' to boolean.");
			}
		}
		else
		{
			if (bool === '0')
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}
}

function validateEmail(email) {
	var t = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	
	return t.test(email);
}

module.exports = {
	cloneObject: cloneObject,
	formatNumber: formatNumber,
	parseBoolean: parseBoolean,
	validateEmail: validateEmail
};