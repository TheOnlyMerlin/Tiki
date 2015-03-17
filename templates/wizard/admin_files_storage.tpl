{* $Id$ *}

{icon name="admin_fgal" size=3 iclass="pull-right"}
<div class="media">
	<img class="pull-left" src="img/icons/large/wizard_admin48x48.png" alt="{tr}Configuration Wizard{/tr}" title="{tr}Configuration Wizard{/tr}" />
	<div class="media-body">
		<p>
			{if isset($promptElFinder) AND $promptElFinder eq 'y'}
				<div>
					<fieldset>
						<legend>{tr}elFinder{/tr}</legend>
						<img src="img/icons/large/file-manager.png" class="adminWizardIconright" />
						<input type="checkbox" name="useElFinderAsDefault" {if !isset($useElFinderAsDefault) or $useElFinderAsDefault eq true}checked='checked'{/if} /> {tr}Set elFinder as the default file gallery viewer{/tr}.
						<div class="adminoptionboxchild">
							{tr}See also{/tr} <a href="http://doc.tiki.org/elFinder" target="_blank">{tr}elFinder{/tr} @ doc.tiki.org</a>
						</div>
						<br>
					</fieldset>
				</div>
			{/if}
			{if isset($promptFileGalleryStorage) AND $promptFileGalleryStorage eq 'y'}
				<div>
					<fieldset>
						<img src="img/icons/large/file-manager.png" class="adminWizardIconright" />
						<legend>{tr}File Gallery storage{/tr}</legend>
						{preference name='fgal_use_dir'}
					</fieldset>
				</div>
			{/if}
			{if isset($promptAttachmentStorage) AND $promptAttachmentStorage eq 'y'}
				<div>
					<fieldset>
						<legend>{tr}Attachment storage{/tr}</legend>
						<img src="img/icons/large/wikipages.png" class="adminWizardIconright" />
						{preference name=w_use_db}
						{preference name=w_use_dir}
					</fieldset>
				</div>
			{/if}
		</p>
	</div>
</div>
