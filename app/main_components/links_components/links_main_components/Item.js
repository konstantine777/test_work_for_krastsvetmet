import React, { Component } from 'react';
import { POST } from './../../../request/requestFetch';

export default class Item extends Component
{

	constructor()
	{
		super();
		this.clickToLink = this.clickToLink.bind(this);
	}

	clickToLink()
	{

		let {state, refreshState, getCurrentState} = this.props.state;
		let {id} = this.props.content;

		refreshState(Object.assign({}, state, {
			page: 'loading'
		}));

		POST('reference/statistic', {alias: Number(id)})
			.then((answer) => answer.json())
			.then((result) => refreshState(Object.assign({}, state, {
					page: 'statistic',
					statistic: result
				})
			))

	}

	render()
	{

		let {short_url, best_before, is_commercial, url, domain} = this.props.content;

		let best_before_result;

		if(best_before === 'infinity') best_before_result = 'Бессрочная';
		else best_before_result = 'Действует до ' + new Date(best_before).toLocaleString().replace(', ', ' ');

		return(
			<section className={(new Date() < new Date(best_before) || best_before === 'infinity') ? 'reference set' : 'reference'} onClick={this.clickToLink}>
				<div>
					<div>{url}</div>
					<div>{domain + short_url}</div>
				</div>
				<div>
					<div>{(is_commercial) ? 'Коммерческая' : 'Прямая'}</div>
					<div>{best_before_result}</div>
				</div>
			</section>
		)

	}

}