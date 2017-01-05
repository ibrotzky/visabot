function cloneObject(obj)
{
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

module.exports = {
	cloneObject: cloneObject,
	formatNumber: formatNumber,
	parseBoolean: parseBoolean
};