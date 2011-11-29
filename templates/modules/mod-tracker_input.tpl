{if $tracker_input.trackerId}
{tikimodule error=$module_params.error title=$tpl_module_title name="tracker_input" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
	<form class="mod-tracker-input simple" method="get" action="{service controller=tracker action=insert_item}" data-location="{$tracker_input.location|escape}">
		{foreach from=$tracker_input.textInput key=token item=label}
			<label>
				{$label|escape}
				<input type="text" name="forced~{$token|escape}"/>
			</label>
		{/foreach}
		<div class="submit">
			<input type="hidden" name="trackerId" value="{$tracker_input.trackerId|escape}"/>
			<input type="hidden" name="controller" value="tracker"/>
			<input type="hidden" name="action" value="insert_item"/>
			<input type="submit" value="{tr}Create{/tr}"/>
			{foreach from=$tracker_input.hiddenInput key=f item=v}
				<input id="{$f|escape}" type="hidden" name="forced~{$f|escape}" value="{$v|escape}"/>
			{/foreach}
		</div>
	</form>

	{jq}
	function hasEmptyField(form, scope) {
		var hasEmpty = false;
		$(scope, form).each(function () {
			if ($(this).val() === '') {
				hasEmpty = true;
			}
		});

		return hasEmpty;
	}
	$('.mod-tracker-input').removeClass('mod-tracker-input').submit(function () {
		var form = this;
		if (hasEmptyField(form, ':input:not(:submit)')) {
			return false;
		}

		$(this).serviceDialog({
			title: $(':submit', form).val(),
			data: $(form).serialize(),
			success: function () {
				$(form).trigger('insert');
			}
		});
		return false;
	}).each(function () {
		var form = this, location = $(this).data('location');

		if (location ) {
			$(':submit', form).hide();
			setTimeout(function () {
				var control, button, map, modeManager, newMode, oldMode;
				map = $(form).closest('.tab, #appframe, body').find('.map-container');
				modeManager = map[0].modeManager;
				control = map.setupMapSelection({
					field: $('#' + location),
					click: function () {
						$(form).submit();
					}
				});
				control.deactivate();

				oldMode = modeManager.activeMode.name;
				modeManager.addMode({name: newMode = "{{$tpl_module_title}}", controls: [control]});

				button = $('<input type="submit"/>')
					.val('{tr}Add Marker{/tr}')
					.button()
					.click(function () {
						modeManager.switchTo(newMode);
						return false;
					})
					.appendTo(form);

				$(':text', form).keyup(function (e) {
					button.button(hasEmptyField(form, ':text') ? 'disable' : 'enable');

					if (e.which === 13) {
						button.click();
					}
				}).keyup();

				$(form).bind('insert', function () {
					modeManager.switchTo(oldMode);
				});
			}, 500);
		}
	});
	{/jq}
{/tikimodule}
{else}
{tr}Permission denied{/tr}
{/if}
