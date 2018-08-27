import React, { Component } from 'react';

export default class Join extends Component
{

	constructor()
	{
		super();
		this.change = this.change.bind(this);
	}

	change(e)
	{

		let {state, refreshState} = this.props.state;
		let reestablishAccount = state.reestablishAccount;
		let elem = e.currentTarget;

		refreshState(Object.assign({}, state, {
			reestablishAccount: Object.assign({}, reestablishAccount, {
				join: elem.checked
			})
		}))

	}

	render()
	{

		let {state} = this.props.state;
		let {join} = state.reestablishAccount.join;

		return(
			<label>
				<input type="checkbox" title="Введите ваш ключ" id="join" checked={join} />
				<label htmlFor="join" className="checkbox_label" />
				<span>Объеденить</span>
			</label>
		)

	}

}