import React, { Component } from 'react';
import { POST } from '../../../request/requestFetch';

export default class ButtonSand extends Component
{

	constructor()
	{
		super();
		this.clickToButton = this.clickToButton.bind(this);
	}

	clickToButton(e)
	{

		e.preventDefault();

		let {state, refreshState} = this.props.state;
		let {key, join} = state.reestablishAccount;

		refreshState(Object.assign({}, state, {
			page: 'loading'
		}));

		POST('reestablish/reestablish', {
			key: key,
			combine: join
		}).then((answer) => answer.json())
			.then((obj) => refreshState(Object.assign({}, state, {
				page: 'links',
				successGetRight: obj.success,
				linksList: obj.aliases,
				keyFormValidation: null,
				reestablishAccount: {
					key: '',
					join: false
				}
			})))

	}

	render()
	{

		let {state} = this.props.state;
		let accept = state.keyFormValidation;

		return(
			<label>
				<button disabled={!accept} onClick={this.clickToButton}>Восстановить</button>
			</label>
		)

	}

}