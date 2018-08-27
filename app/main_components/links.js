import React from 'react';
import ElseHeader from './links_components/elseHeader';
import LinksMain from './links_components/linksMain';

export default (props) =>
	(
		<div>
			<ElseHeader state={props.state} />
			<LinksMain state={props.state} />
		</div>
	)