import React from 'react';

export default (props) =>
{

	let {state} = props.state;
	let {statistic} = state;
	let aliasData = statistic.alias;
	let {total_conversions, unique_conversions, alias} = aliasData;

	return(
		<div className="my_ref">
			<div>{alias}</div>
			<div>{unique_conversions}</div>
			<div>{total_conversions}</div>
		</div>
	)

}