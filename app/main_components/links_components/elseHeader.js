import React, { Component } from 'react';
import ElseHeaderWrapper from './else_header_components/alseHeaderWrapper';
import FormWrapper from './else_header_components/formWrapper';
import RecoveryKey from "./else_header_components/RecoveryKey";
import Join from "./else_header_components/Join";
import ButtonSand from "./else_header_components/ButtonSand";

export default (props) =>
	(
		<ElseHeaderWrapper>
			<FormWrapper>
				<RecoveryKey state={props.state} />
				<Join  state={props.state}/>
				<ButtonSand state={props.state} />
			</FormWrapper>
		</ElseHeaderWrapper>
	)