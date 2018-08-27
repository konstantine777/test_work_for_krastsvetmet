import React from 'react';

export default (props) =>
{

	let {state} = props.state;
	let {linksList, successGetRight} = state;
	let message;

	if(successGetRight === null)  message = '';
	else if(successGetRight) message = <div className="success message">Все прошло успешно. Ваши ссылки обновлены.</div>;
	else message = <div className="error message">Не верный уникальный код. Проверьте правильность набора.</div>;

	return(
		<div className="main links_main">
			{message}
			<section className="links_description">
				<div>Здесть отображены все создоваемые вами ссылки.</div>
				<div>{linksList.unique_key}</div>
			</section>
			{props.children}
		</div>
	)

}

	// <div className="error_message">Не верный уникальный код, проверьте правильность набора.</div>