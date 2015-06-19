{* $Id$ *}
{if empty($user) || $user eq 'anonymous' || !empty($showantibot)}
	<div class="form-group">
		{if $captchalib->type eq 'recaptcha'}
			{$captchalib->render()}
		{elseif $captchalib->type eq 'questions'}
			<input type="hidden" name="captcha[id]" id="captchaId" value="{$captchalib->generate()}">
			<label class="col-md-4 col-sm-3 control-label">
				{$captchalib->render()}
				{if $showmandatory eq 'y'}<span class="mandatory_star"> *</span>{/if}
			</label>
			<div class="col-md-8 col-sm-9">
				<input class="form-control" type="text" maxlength="8" size="22" name="captcha[input]" id="antibotcode">
			</div>
		{else}
			<input type="hidden" name="captcha[id]" id="captchaId" value="{$captchalib->generate()}">
			<label class="control-label" for="antibotcode">{tr}Enter what you see{/tr}{if $showmandatory eq 'y'}<span class="attention"> *</span>{/if}</label>
			<input class="form-control" type="text" maxlength="8" size="22" name="captcha[input]" id="antibotcode">
			{if $captchalib->type eq 'default'}
				<img id="captchaImg" src="{$captchalib->getPath()}" alt="{tr}Anti-Bot verification code image{/tr}" height="50">
			{else}
				{* dumb captcha *}
				{$captchalib->render()}
			{/if}
		{/if}

		{if $captchalib->type eq 'default'}
			{button _id='captchaRegenerate' _class='' href='#antibot' _text='{tr}Try another code{/tr}' _icon_name="refresh" _onclick="generateCaptcha()"}
		{/if}
	</div>
{/if}
{jq}
if($("#antibotcode").parents('form').data("validator")) {
	$( "#antibotcode" ).rules( "add", {
		required: true,
		remote: {
			url: "validate-ajax.php",
			type: "post",
			data: {
				validator: "captcha",
				parameter: function() {
					return $("#captchaId").val();
				},
				input: function() {
					return $("#antibotcode").val();
				}
			}
		}
	});
} else if (jqueryTiki.validate) {
	$("#antibotcode").parents('form').validate({
		rules: {
			"captcha[input]": {
				required: true,
				remote: {
					url: "validate-ajax.php",
					type: "post",
					data: {
						validator: "captcha",
						parameter: function() {
							return $("#captchaId").val();
						},
						input: function() {
							return $("#antibotcode").val();
						}
					}
				}
			}
		},
		messages: {
			"captcha[input]": { required: "This field is required"}
		},
		submitHandler: function(){form.submit();}
	});
}
{/jq}
