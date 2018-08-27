export function genKey(len) {

	let generationString = 'abcdefghijklmnopqrstuvwxwz1234567890';
	let resultKey = '';

	while (len !== 0)
	{

		let position = (Math.random() * (generationString.length - 1)).toFixed(0);

		resultKey += generationString[position];

		len--;

	}

	return resultKey;
}
