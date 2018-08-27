import React, {Component} from 'react';

export default class UserURL extends Component
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
		let data = state.homeSandData;

		if(elem.value === '') return refreshState({
			homeSandData: Object.assign({}, data, {
				URL: ''
			}),
			validRef: null
		});

		let template = /^(http|https):\/\/[^ "]+$/;
		let result = template.test(elem.value);

		refreshState({
			homeSandData: Object.assign({}, data, {
				URL: elem.value
			}),
			validRef: result
		});



	}

	render()
	{

		let {state} = this.props.state;
		let dataSend = state.homeSandData;

		let class_name;

		if(state.validRef === null) class_name = 'url_input';
		else if(state.validRef) class_name = 'url_input existing';
		else class_name = 'url_input incorrect';

		return(
			<label className="line">
				Ведите ваш url, который необходимо сократить
				<input type="text" title="введите ссылку" className={class_name} defaultValue={dataSend.URL} onChange={this.change} placeholder="http://" />
			</label>
		);

	}

}