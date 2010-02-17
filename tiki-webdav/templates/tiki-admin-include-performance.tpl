{* $Id$ *}

{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Please see the <a class='rbox-link' target='tikihelp' href='http://dev.tikiwiki.org/Performance'>Performance page</a> on Tiki's developer site.{/tr}{/remarksbox}

<form class="admin" id="performance" name="performance" action="tiki-admin.php?page=performance" method="post">
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" name="performance" value="{tr}Apply{/tr}" />
		<input type="reset" name="performancereset" value="{tr}Reset{/tr}" />
	</div>

		<fieldset>
			<legend>{tr}Performance{/tr}</legend>
			{preference name=tiki_minify_javascript}
			{preference name=javascript_cdn}
			{preference name=tiki_minify_css}
			<div class="adminoptionboxchild" id="tiki_minify_css_childcontainer">
				{preference name=tiki_minify_css_single_file}
			</div>
			{preference name=feature_obzip}
			{preference name=users_serve_avatar_static}
			<div class="adminoptionboxchild">
				{if $gzip_handler ne 'none'}
					<div class="highlight" style="margin-left:30px;">
						{tr}Output compression is active.{/tr}
						<br />
						{tr}Compression is handled by{/tr}: {$gzip_handler}.
					</div>
				{/if}
			</div>
			{preference name=tiki_cachecontrol_session}
			{preference name=tiki_cachecontrol_nosession}
		</fieldset>
		
		<fieldset>
			<legend>{tr}Wiki{/tr}</legend>
			{preference name=wiki_cache}
			{preference name=feature_wiki_icache}
			{preference name=wiki_ranking_reload_probability}
		</fieldset>

		<fieldset>
			<legend>{tr}Search{/tr}</legend>
			{remarksbox type="tip" title="{tr}Tip{/tr}"}
				{tr}Many search options impact performance. Please see <a href="tiki-admin.php?page=search">Search admin panel</a>.{/tr}
			{/remarksbox}
		</fieldset>

			<fieldset>
				<legend>{tr}Database{/tr}</legend>
				{preference name=log_sql}
				<div class="adminoptionboxchild" id="log_sql_childcontainer">
					{preference name=log_sql_perf_min}
				</div>
			</fieldset>		
		
		<fieldset>
			<legend>{tr}Memcache{/tr}</legend>
			{preference name=memcache_enabled}
			<div class="adminoptionboxchild" id="memcache_enabled_childcontainer">
				{preference name=memcache_compress}
				{preference name=memcache_prefix}
				{preference name=memcache_expiration}
				{preference name=memcache_servers}
				{preference name=memcache_wiki_data}
				{preference name=memcache_wiki_output}
				{preference name=memcache_forum_output}
			</div>
		</fieldset>

		<fieldset>
			<legend>{tr}Plugins{/tr}</legend>
			{preference name=wikiplugin_snarf_cache}
		</fieldset>
	
	<div class="input_submit_container" style="margin-top: 5px; text-align: center">
		<input type="submit" name="performance" value="{tr}Apply{/tr}" />
	</div>
</form>
