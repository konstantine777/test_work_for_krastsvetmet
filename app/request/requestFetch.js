function parsObjectToURL(obj)
{

	let URLVars = [];

	for (let key in obj)
	{

		if(obj.hasOwnProperty(key))
		{

			URLVars.push(key + '=' + JSON.stringify(obj[key]))

		}

	}

	return URLVars.join('&');

}

export function POST(url, objVars)
{

	let URLVars = parsObjectToURL(objVars);
	let session = document.cookie;

	let breakSession = session.split('=');

	return fetch(url, {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
			"sessionid": breakSession[1]
		},
		body: URLVars
	})

}

