import React from 'react';

export default (props) =>
{
	let {state} = props.state;
	let {alias, duration, unique_key} = state.refData;

	let timeString;

	if(duration === 'infinity') timeString = true;
	else timeString = new Date(duration).toLocaleString().replace(', ', ' в ');

	return(
		<div className="main">
			<section>
				<article>
					<h2>Поздравляю, вы создали ссылку.</h2>
					<span className="code_description">
				Обратите внимание на код ниже, он необходим для
				доступа к статистике, а так же синхронизации данных
				нескольких аккаунтов, которые создаются автоматически,
				вам следует сохранить этот код в надежном месте.
			</span>
					<div className="code">{unique_key}</div>
				</article>
				<article className="href_description">
					<div>Теперь ваша ссылка доступна по этому адрессу:</div>
					<a target="_blank" href={alias}>{alias}</a>
					{
						(timeString === true) ?
							<div>
								Обратите внимание, что это бессрочная ссылка,
								она останется активной на протяжении работы сервиса.
							</div> :
							<div>
								Обратите внимание, данная ссылка не бессрочная,
								она будет деактивированна {timeString}, но ее статистику по прежнему
								можно будет смотреть.
							</div>
					}
				</article>
			</section>
		</div>
	)

}
