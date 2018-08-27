import React from 'react';

export default (props) =>
	(
		<div className="else_header user_form">
			<div>Форма восстановления</div>
			{props.children}
		</div>
	)