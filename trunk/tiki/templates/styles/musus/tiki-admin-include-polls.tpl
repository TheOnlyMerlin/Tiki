<div class="tiki">
<div class="tiki-title">{tr}Poll settings{/tr}</div>
<div class="tiki-content">
    <div class="simplebox">
    {tr}Poll comments settings{/tr}
    <form method="post" action="tiki-admin.php?page=polls">
    <table class="admin">
    <tr><td><label>{tr}Comments{/tr}:</label></td><td><input type="checkbox" name="feature_poll_comments" {if $feature_poll_comments eq 'y'}checked="checked"{/if}/></td></tr>
    <tr><td><label>{tr}Default number of comments per page{/tr}: </label></td><td><input size="5" type="text" name="poll_comments_per_page" value="{$poll_comments_per_page|escape}" /></td></tr>
    <tr><td><label>{tr}Comments default ordering{/tr}:</label>
    </td><td>
    <select name="poll_comments_default_ordering">
    <option value="commentDate_desc" {if $poll_comments_default_ordering eq 'commentDate_desc'}selected="selected"{/if}>{tr}Newest first{/tr}</option>
	<option value="commentDate_asc" {if $poll_comments_default_ordering eq 'commentDate_asc'}selected="selected"{/if}>{tr}Oldest first{/tr}</option>
    <option value="points_desc" {if $poll_comments_default_ordering eq 'points_desc'}selected="selected"{/if}>{tr}Points{/tr}</option>
    </select>
    </td></tr>
    <tr><td colspan="2" class="button"><input type="submit" name="pollprefs" value="{tr}Change preferences{/tr}" /></td></tr>
    </table>
    </form>
    </div>
</div>
</div>