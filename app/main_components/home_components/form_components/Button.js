import React, {Component} from 'react';
import { POST } from '../../../request/requestFetch';

export default class Button extends Component
{

	constructor()
	{
		super();
		this.click = this.click.bind(this);
	}

	click(e)
	{
		e.preventDefault();

		let {state, refreshState} = this.props.state;
		let dataSend = state.homeSandData;
		let {URL, self_URL, self_URL_string, commercial, available_until} = dataSend;

		refreshState(Object.assign({}, state, {page: 'loading'}));

		POST('reference/add', {
			link: URL,
			rand_alias: !self_URL,
			self_alias: self_URL_string,
			duration: available_until,
			commercial_link: commercial
		})
			.then((answer) => answer.json())
			.then((obj) => refreshState(Object.assign({}, state, {
				page: 'alias',
				refData: obj,
				validRef: null,
				existingURL: null,
				homeSandData: {
					URL: '',
					self_URL: false,
					self_URL_string: '',
					commercial: false,
					available_until: 'infinity'
				}
			})))

	}

	render()
	{

		let {state} = this.props.state;
		let dataSend = state.homeSandData;
		let disabled = true;

		let{validRef, existingURL} = state;
		let{available_until, self_URL, self_URL_string} = dataSend;

		if(validRef && ((self_URL && self_URL_string !== '' && existingURL) || (!self_URL && self_URL_string === '' && !existingURL)) && (new Date() < new Date(available_until) || available_until === 'infinity'))
		{
			disabled = false;
		}

		return(
			<label className="line">
				<button onClick={this.click} disabled={disabled}>Уменьшить</button>
			</label>
		);

	}

}