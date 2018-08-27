import React, {Component} from 'react';

export default class IsInfinity extends Component
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

		let incorrect_element = document.getElementsByClassName('incorrect')[0];

		if (incorrect_element) incorrect_element.classList.remove('incorrect');

		let date = new Date();

		date.setHours(date.getHours() + 2);

		let localDate = date.toLocaleString();

		let breakString = localDate.split(', ');

		let dateComponents = breakString[0].split('.');
		let timeComponents = breakString[1].split(':');



		let dateDefault = `${dateComponents[2]}-${dateComponents[1]}-${dateComponents[0]}T${(timeComponents[0].length === 1 ? '0' + timeComponents[0] : timeComponents[0])}:${timeComponents[1]}`;

		(elem.checked) ?
			refreshState({homeSandData: Object.assign({}, data, {available_until: 'infinity'})})
			:
			refreshState({homeSandData: Object.assign({}, data, {available_until: dateDefault})})

	}

	render()
	{

		let {state} = this.props.state;

		return(
			<label className="line">
				<input id="infinity_ref" type="checkbox" onChange={this.change} checked={state.homeSandData.available_until === 'infinity'} />
				<label htmlFor="infinity_ref" className="checkbox_label" />
				Бессрочное действие
			</label>
		);

	}

}