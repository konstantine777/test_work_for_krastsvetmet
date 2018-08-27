import React from 'react';

export default (props) =>
	(
		<article className="user_form">
			<form>
				{props.children}
			</form>
		</article>
	)