import React, {Component} from 'react';

export default class Commercial extends Component
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

		refreshState({homeSandData: Object.assign({}, data, {commercial: elem.checked})});

	}

	render()
	{

		let {state} = this.props.state;
		let dataSend = state.homeSandData;

		return(
			<label className="line">
				<input type="checkbox" id="check" onChange={this.change} checked={dataSend.commercial} />
				<label htmlFor="check" className="checkbox_label" />
				Комерческая ссылка
			</label>
		);

	}

}