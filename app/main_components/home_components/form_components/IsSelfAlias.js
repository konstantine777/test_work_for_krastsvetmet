import React, {Component} from 'react';

export default class IsSelfAlias extends Component
{

	constructor()
	{
		super();
		this.change = this.change.bind(this);
	}

	change(e)
	{

		let {state, refreshState} = this.props.state;
		let data = state.homeSandData;

		let elem = e.currentTarget;

		refreshState({
			homeSandData: Object.assign({}, data, {
				self_URL: elem.checked,
				self_URL_string: ''
			}),
			existingURL: null,
			availabilityRefLoading: false,
			expectedResponses: []
		});

	}

	render()
	{

		let {state} = this.props.state;
		let data = state.homeSandData;

		return(
			<label className="line">
				<input id="custom_name" type="checkbox" onChange={this.change} checked={data.self_URL}/>
				<label htmlFor="custom_name" className="checkbox_label" />
				Собственная ссылка
			</label>
		);

	}

}