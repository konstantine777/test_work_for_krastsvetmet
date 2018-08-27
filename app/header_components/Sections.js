import React, { Component } from 'react';
import { POST } from './../request/requestFetch';

export default class Section extends Component
{

	constructor()
	{
		super();
		this.listClick = this.listClick.bind(this);
	}

	listClick()
	{

		let {state, refreshState, getCurrentState} = this.props.state;

		if(getCurrentState().page !== 'links')
		{

			if(getCurrentState().loadingLinks) return;

			refreshState(Object.assign({}, state, {
				page: 'loading',
			}));

			POST('reference/aliases', {})
				.then((array) => array.json())
				.then((result) => refreshState(Object.assign({}, state, {
					page: 'links',
					linksList: result,
					loadingLinks: false,
					successGetRight: null
				})))

		}

	}

	render()
	{

		let {state, refreshState} = this.props.state;

		return(
			<section className="list_wrapper">
				<ul className="list">
					<li className={(state.page === 'home') ? 'action' : ''} onClick={refreshState.bind(null, {page: 'home'})}>ГАВНАЯ</li>
					<li className={(state.page === 'links') ? 'action' : ''} onClick={this.listClick}>МОИ ССЫЛКИ</li>
				</ul>
			</section>
		);

	}

}