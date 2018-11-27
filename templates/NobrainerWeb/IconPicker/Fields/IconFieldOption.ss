<% if $Options %>
    <optgroup label="$Title.ATT">
		<% loop $Options %>
			<% include NobrainerWeb/IconPicker/Fields/IconFieldOption %>
		<% end_loop %>
    </optgroup>
<% else %>
    <option value="$Value.ATT"
		<% if $Selected %> selected="selected"<% end_if %>
		<% if $Disabled %> disabled="disabled"<% end_if %>
		<% if Icon %>data-icon="$Icon"<% end_if %>
    >
		<% if $Title %><span class="nw-iconpicker-title">$Title.XML</span><% else %>&nbsp;<% end_if %>
	</option>
<% end_if %>
