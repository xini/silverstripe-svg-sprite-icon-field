<ul $AttributesHTML>
	<% loop $Options %>
		<li class="option">
			<input id="$ID" class="radio" name="$Name" type="radio" value="$Value"<% if $isChecked %> checked<% end_if %> />
			<label for="$ID">
                <% if $Value %>
                    <svg aria-hidden="true" focusable="false" role="img" width="40" height="40">
                        <use href="$URL" xlink:href="$URL"></use>
                    </svg>
                    <span>$Label</span>
                <% end_if %>
            </label>
		</li>
	<% end_loop %>
</ul>
