import React, {Component} from 'react';
import { genKey } from './helper/random_key';
import {POST} from '../../../request/requestFetch';
import Loader from './loader/loader';

export default class SelfAliasText extends Component
{

	constructor()
	{
		super();
		this.change = this.change.bind(this);
	}

	change(e)
	{

		let elem = e.currentTarget;
		let {state, refreshState, getCurrentState} = this.props.state;
		let data = state.homeSandData;
		let expectedResponses = state.expectedResponses;
		let token = genKey(10);

		if(elem.value === '') return refreshState({
			homeSandData: Object.assign({}, data, {
				self_URL_string: ''
			}),
			existingURL: null,
			availabilityRefLoading: false,
			expectedResponses: []
		});

		refreshState({
			homeSandData: Object.assign({}, data, {
				self_URL_string: elem.value
			}),
			availabilityRefLoading: true,
			expectedResponses: expectedResponses.concat([token])
		});

		POST('check/self', {ref: elem.value})
			.then((answer) => answer.json())
			.then((obj) => {

				let currentExpectedResponses = getCurrentState().expectedResponses;

				if(currentExpectedResponses.length === 0) return;

				let tokenPosition = currentExpectedResponses.indexOf(token);

				let before = currentExpectedResponses.splice(0, tokenPosition);

				let after = currentExpectedResponses.splice(tokenPosition + 1, currentExpectedResponses.length);

				let resultExpectedResponses = before.concat(after);

				if(resultExpectedResponses.length !== 0)
				{

					return refreshState({
						expectedResponses: resultExpectedResponses
					})

				}

				return refreshState({
					existingURL: obj.answer,
					availabilityRefLoading: false,
					expectedResponses: resultExpectedResponses
				})

			});

	}

	render()
	{

		let {state} = this.props.state;
		let dataSend = state.homeSandData;

		let class_name;

		if(state.existingURL === null) class_name = 'url_input';
		else if(state.existingURL) class_name = 'url_input existing';
		else class_name = 'url_input incorrect';

		return(
			<label className={(dataSend.self_URL) ? 'line' : 'line disabled'}>
				{domain}
				<div className="input_wrapper">
					<input type="text" title="введите ссылку" onChange={this.change} value={dataSend.self_URL_string} className={class_name} placeholder="ваша ссылка" disabled={!dataSend.self_URL} />
					{(state.availabilityRefLoading) ? <Loader/> : ''}
				</div>
			</label>
		);

	}

}