<% if EditList %>
	<div class="$extraClass">
		<table>
			<% loop EditList %>
				<tr>
					<td>
						$Title
					</td>
					<td>
						<a href="$Up.EditLink($ID)" title="Edit">edit</a>
					</td>
					<td>
						<a href="$Up.RemoveLink($ID)" title="Remove">remove</a>
					</td>
				</tr>
			<% end_loop %>
		</table>
	</div>
<% end_if %>