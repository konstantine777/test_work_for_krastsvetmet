import React from 'react';
import LinksMainWrapper from './links_main_components/linksMainHeaderWrapper';
import Item from "./links_main_components/Item";

export default (props) =>
{
	let {state} = props.state;
	let {aliases} = state.linksList;

	return(
		<LinksMainWrapper state={props.state}>
			{
				(aliases.length === 0) ?
					<div className="none">У вас пока еще нет созданных ссылок</div> :
					aliases.map((arr, index) =>(
						<Item state={props.state} key={index} content={arr} />
					))
			}
		</LinksMainWrapper>
	)

}
