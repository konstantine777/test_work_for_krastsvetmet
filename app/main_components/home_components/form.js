import React from 'react';
import FormWrapper from './form_components/formWrapper';
import UserURL from "./form_components/UserURL";
import IsSelfAlias from './form_components/IsSelfAlias';
import SelfAliasText from "./form_components/SelfAliasText";
import Commercial from "./form_components/Commercial";
import IsInfinity from "./form_components/IsInfinity";
import Validity from "./form_components/Validity";
import Button from "./form_components/Button";

export default (props) =>
	(
		<FormWrapper>
			<UserURL state={props.state} />
			<IsSelfAlias state={props.state} />
			<SelfAliasText state={props.state} />
			<Commercial state={props.state} />
			<IsInfinity state={props.state} />
			<Validity state={props.state} />
			<Button state={props.state} />
		</FormWrapper>
	)