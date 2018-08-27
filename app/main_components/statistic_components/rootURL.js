import React from 'react';

export default (props) =>
{

	let {state} = props.state;
	let {statistic} = state;
	let aliasData = statistic.original;
	let {total_conversions, unique_conversions, url} = aliasData;

	return(
		<div className="root_url">
			<div>{url}</div>
			<div>{unique_conversions}</div>
			<div>{total_conversions}</div>
		</div>
	)

}