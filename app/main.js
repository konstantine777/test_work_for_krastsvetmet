import React from 'react';
import Home from './main_components/home';
import Links from './main_components/links';
import Statistic from './main_components/statistic';
import Loading from './main_components/loading';
import CreatedAlias from './main_components/createdAlias';

export default (props) =>
{

	let {state} = props.state;

	switch (state.page)
	{

		case 'home':
			return <Home state={props.state} />;

		case 'links':
			return <Links state={props.state} />;

		case 'statistic':
			return <Statistic state={props.state} />;

		case 'loading':
			return <Loading state={props.state} />;

		case 'alias':
			return <CreatedAlias state={props.state} />;

	}

}
