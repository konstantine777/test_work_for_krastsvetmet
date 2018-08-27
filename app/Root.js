import React, {Component} from 'react';
import Header from './header';
import RootWrapper from './rootWrapper';
import Main from './main';

export default class Root extends Component
{

	constructor()
	{
		super();
		this.state = {
			page: 'home',
			loadingLinks: false,
			refDataReceived: false,
			availabilityRefLoading: false,
			validRef: null,
			expectedResponses: [],
			existingURL: null,
			successGetRight: null,
			linksList: [],
			statistic: {},
			refData: [],
			keyFormValidation: null,
			reestablishAccount: {
				key: '',
				join: false
			},
			homeSandData: {
				URL: '',
				self_URL: false,
				self_URL_string: '',
				commercial: false,
				available_until: 'infinity'
			}

		};

		this.refreshState = this.refreshState.bind(this);
		this.getCurrentState = this.getCurrentState.bind(this);
	}

	refreshState(updatable)
	{

		this.setState(function (last) {
			return Object.assign({}, last, updatable)
		});

	}

	getCurrentState()
	{

		return this.state;

	}

	render()
	{

		let state = this.state;
		let refreshState = this.refreshState;
		let getCurrentState = this.getCurrentState;

		let allStateMethod = {state, refreshState, getCurrentState};

		return(
			<RootWrapper>
				<Header state={allStateMethod}/>
				<Main state={allStateMethod}/>
			</RootWrapper>
		);

	}

}