import React from 'react';

export default (props) =>
	(
		<div className="main">
			<section className="statistic_wrapper">
				{props.children}
			</section>
		</div>
	)