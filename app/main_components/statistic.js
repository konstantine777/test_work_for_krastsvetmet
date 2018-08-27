import React from 'react';
import StatisticWrapper from './statistic_components/statisticWrapper';
import Manual from './statistic_components/manual';
import Alias from './statistic_components/alias';
import RootURL from './statistic_components/rootURL';

export default (props) =>
	(
		<StatisticWrapper>
			<Manual />
			<Alias state={props.state} />
			<RootURL state={props.state} />
		</StatisticWrapper>
	)