import React, { Component } from 'react';

export default class RecoveryKey extends Component
{

	constructor()
	{
		super();
		this.change = this.change.bind(this);
	}

	change(e)
	{

		let elem = e.currentTarget;
		let {state, refreshState} = this.props.state;
		let {linksList, reestablishAccount} = state;

		if(elem.value === '') return refreshState(Object.assign({}, state, {
			keyFormValidation: null,
			reestablishAccount: Object.assign({}, reestablishAccount, {
				key: ''
			})
		}));

		if(elem.value === linksList.unique_key) return refreshState(Object.assign({}, state, {
			keyFormValidation: false,
			reestablishAccount: Object.assign({}, reestablishAccount, {
				key: elem.value
			})
		}));

		let breakKey = elem.value.split('-');

		if(breakKey.length === 3 && breakKey[0].length === 4 && breakKey[1].length === 4 && breakKey[2].length === 4)
			refreshState(Object.assign({}, state, {
			keyFormValidation: true,
			reestablishAccount: Object.assign({}, reestablishAccount, {
				key: elem.value
			})
		}));
		else refreshState(Object.assign({}, state, {
			keyFormValidation: false,
			reestablishAccount: Object.assign({}, reestablishAccount, {
				key: elem.value
			})
		}));

	}

	render()
	{

		let {state} = this.props.state;
		let reestablishAccount = state.reestablishAccount;
		let className;

		if(state.keyFormValidation === null) className = 'url_input';
		else if(state.keyFormValidation) className = 'url_input existing';
		else className = 'url_input incorrect';

		return(
			<label>
				<input type="text" className={className} placeholder="Ключ восстановления" title="Введите ваш ключ" value={reestablishAccount.key} onChange={this.change} />
			</label>
		)

	}

}