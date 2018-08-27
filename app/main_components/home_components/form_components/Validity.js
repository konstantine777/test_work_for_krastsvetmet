import React, {Component} from 'react';

export default class Validity extends Component
{

	constructor()
	{
		super();
		this.change = this.change.bind(this);
	}

	change(e)
	{

		let {state, refreshState} = this.props.state;

		let sendData = state.homeSandData;

		if(new Date() >= new Date(e.currentTarget.value)) e.currentTarget.classList.add('incorrect');
		else e.currentTarget.classList.remove('incorrect');

		refreshState(
			{homeSandData: Object.assign({}, sendData, {available_until: e.currentTarget.value})}
		);

	}

	render()
	{

		let {state} = this.props.state;
		let dataSend = state.homeSandData;

		return(
			<label className="line">
				<input type="datetime-local" title="Укажите срок действия" className="url_input" onChange={this.change} value={(dataSend.available_until !== 'infinity') ? dataSend.available_until : '' } disabled={dataSend.available_until === 'infinity'} />
			</label>
		);

	}

}