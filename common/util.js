parseBoolean = function (str) {
	if (str === undefined || str === null)
	{
		return false;
	}
	else
	{
		if (typeof str !== 'string')
		{
			str = str.toString();
		}

		if (isNaN(str))
		{
			switch (str.toLowerCase())
			{
				case "true":
					return true;
				case "false":
					return false;
				default:
					throw new Error("Boolean.parse: Cannot convert string '" + str + "' to boolean.");
			}
		}
		else
		{
			if (str === '0')
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
	parseBoolean: parseBoolean
};