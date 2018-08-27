import React from 'react';
import HomeWrapper from './home_components/homeWrapper';
import Description from './home_components/description';
import Form from './home_components/form';

export default (props) =>
	(
		<HomeWrapper>
			<Description/>
			<Form state={props.state}/>
		</HomeWrapper>
	)