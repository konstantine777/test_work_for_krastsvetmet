import React from 'react';
import HeaderWrapper from './header_components/headerWrapper';
import Logotype from './header_components/logotype';
import Sections from './header_components/Sections';


export default (props) =>
{

	return(
		<HeaderWrapper>
			<Logotype/>
			<Sections state={props.state}/>
		</HeaderWrapper>
	);

}