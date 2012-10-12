<?php
// (c) Copyright 2002-2012 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class JisonParser_PluginTest extends JisonParser_Abstract
{
	public function provider()
	{
		$this->syntaxSets = array(
			"html_plugin" => array(),
			"injected_plugin" => array(),
			"injected_plugin_blocked" => array(),
			"rejected_plugin_default_validation_behavior" => array(),
			"expandingoutline_basic" => array(),
		);
	}

	public function html_plugin()
	{
		$this->parser->setOption(array(
			'is_html' => true,
			'skipvalidation' => true
		));

		$syntax = array(
			"{HTML()}<table><tr><td></td></tr></table>{HTML}"
		,
			"<table><tr><td></td></tr></table>"
		);

		$parsed = $this->parser->parse($syntax[0]);

		$this->parser->resetOption();

		return array("parsed" => $parsed, "syntax" => $syntax);
	}

	public function injected_plugin()
	{
		$this->parser->setOption(array(
			'is_html' => true,
			'skipvalidation' => true
		));

		$this->parser->pluginNegotiator->inject(new WikiPlugin_injected());

		$syntax = array(
			"{INJECTED()}__I've been injected!__{INJECTED}"
		,
			'<div id="injected1" class="wikiplugin_injected" style=""><strong>I\'ve been injected!</strong></div>'
		);

		$parsed = $this->parser->parse($syntax[0]);

		$this->parser->resetOption();

		$this->tryRemoveIdsFromHtmlList($parsed);

		$this->parser->pluginNegotiator->eject("WikiPlugin_injected");

		return array("parsed" => $parsed, "syntax" => $syntax);
	}

	public function injected_plugin_blocked()
	{
		$plugin = new WikiPlugin_injected();
		$plugin->validate = 'all';

		$this->parser->pluginNegotiator->inject($plugin);

		$syntax = array(
			"{INJECTED()}You shouldn't see me.{INJECTED}"
		,
			"<div class=\"cbox\" id=\"\">
<div class=\"cbox-title\">
	<img src=\"img/icons/error.png\" alt=\"Error\" width=\"16\" height=\"16\" style=\"vertical-align:middle\" title=\"Error\" class=\"icon\" />
			Plugin execution pending approval
	</div>
<div class=\"cbox-data\">
			<p>This plugin was recently added or modified. Until an editor of the site validates the parameters, execution will not be possible. </p>
					</div>
</div>"
		);

		$parsed = $this->parser->parse($syntax[0]);

		$this->parser->resetOption();

		$this->tryRemoveIdsFromHtmlList($parsed);

		$this->tryRemoveFingerprintId('injected', $parsed);

		$this->parser->pluginNegotiator->eject("WikiPlugin_injected");

		return array("parsed" => $parsed, "syntax" => $syntax);
	}

	public function rejected_plugin_default_validation_behavior()
	{
		$syntax = array(
			"{HTML()}<div>I shouldn't show up</div>{HTML}"
		,
			"<div class=\"cbox\" id=\"\">
<div class=\"cbox-title\">
	<img src=\"img/icons/error.png\" alt=\"Error\" width=\"16\" height=\"16\" style=\"vertical-align:middle\" title=\"Error\" class=\"icon\" />
			Plugin execution pending approval
	</div>
<div class=\"cbox-data\">
			<p>This plugin was recently added or modified. Until an editor of the site validates the parameters, execution will not be possible. </p>
					</div>
</div>"
		);

		$parsed = $this->parser->parse($syntax[0]);

		$this->parser->resetOption();

		$this->tryRemoveIdsFromHtmlList($parsed);

		$this->tryRemoveFingerprintId('html', $parsed);

		return array("parsed" => $parsed, "syntax" => $syntax);
	}

	public function expandingoutline_basic()
	{
		$syntax = array(
"{EXPANDINGOUTLINE()}
*test
*test
**test
**test
**test
***test
****test
**test
**test
**test
*test{EXPANDINGOUTLINE}"
		,
'<div id="expandingoutline1" class="wikiplugin_expandingoutline" style="">' .
	'<table class="tikiListTable" id="">' .
		'<tr>' .
			'<td>' .
				'<table>' .
					'<tr>' .
						'<td id="" class="tikiListTableLabel tier0" data-trail="1" style="width:60px; text-align: right;">1</td>' .
						'<td class="tikiListTableItem">test</td>' .
					'</tr>' .
				'</table>' .
			'</td>' .
		'</tr>' .
		'<tr>' .
			'<td>' .
				'<table>' .
					'<tr>' .
						'<td id="" class="tikiListTableLabel tier0" data-trail="2" style="width:60px; text-align: right;">2<img class="listImg" src="img/toggle-expand-dark.png" data-altImg="img/toggle-collapse-dark.png" /></td>' .
						'<td class="tikiListTableItem">test</td>' .
					'</tr>' .
					'<tr class="parentTrail2 tikiListTableChild">' .
						'<td colspan="2">' .
							'<table>' .
								'<tr>' .
									'<td>' .
										'<table>'.
											'<tr>' .
												'<td id="" class="tikiListTableLabel tier1" data-trail="2_1" style="width:90px; text-align: right;">2.1</td>' .
												'<td class="tikiListTableItem">test</td>' .
											'</tr>' .
										'</table>' .
									'</td>' .
								'</tr>' .
								'<tr>'.
									'<td>' .
										'<table>' .
											'<tr>' .
												'<td id="" class="tikiListTableLabel tier1" data-trail="2_2" style="width:90px; text-align: right;">2.2</td>' .
												'<td class="tikiListTableItem">test</td>' .
											'</tr>' .
										'</table>' .
									'</td>' .
								'</tr>' .
								'<tr>' .
									'<td>' .
										'<table>' .
											'<tr>' .
												'<td id="" class="tikiListTableLabel tier1" data-trail="2_3" style="width:90px; text-align: right;">2.3<img class="listImg" src="img/toggle-expand-dark.png" data-altImg="img/toggle-collapse-dark.png" /></td>'.
												'<td class="tikiListTableItem">test</td>' .
											'</tr>' .
											'<tr class="parentTrail2_3 tikiListTableChild">' .
												'<td colspan="2">' .
													'<table>' .
														'<tr>'.
															'<td>' .
																'<table>'.
																	'<tr>' .
																		'<td id="" class="tikiListTableLabel tier2" data-trail="2_3_1" style="width:120px; text-align: right;">2.3.1<img class="listImg" src="img/toggle-expand-dark.png" data-altImg="img/toggle-collapse-dark.png" /></td>'.
																		'<td class="tikiListTableItem">test</td>' .
																	'</tr>' .
																	'<tr class="parentTrail2_3_1 tikiListTableChild">'.
																		'<td colspan="2">' .
																			'<table>' .
																				'<tr>'.
																					'<td>'.
																						'<table>'.
																							'<tr>' .
																								'<td id="" class="tikiListTableLabel tier3" data-trail="2_3_1_1" style="width:150px; text-align: right;">2.3.1.1</td>'.
																								'<td class="tikiListTableItem">test</td>' .
																							'</tr>'.
																						'</table>'.
																					'</td>'.
																				'</tr>' .
																			'</table>' .
																		'</td>'.
																	'</tr>'.
																'</table>' .
															'</td>'.
														'</tr>' .
													'</table>' .
												'</td>'.
											'</tr>' .
										'</table>' .
									'</td>'.
								'</tr>'.
									'<tr>'.
										'<td>'.
											'<table>'.
												'<tr>'.
													'<td id="" class="tikiListTableLabel tier1" data-trail="2_4" style="width:90px; text-align: right;">2.4</td>'.
													'<td class="tikiListTableItem">test</td>'.
												'</tr>'.
											'</table>'.
										'</td>'.
									'</tr>'.
									'<tr>'.
										'<td>'.
											'<table>'.
												'<tr>'.
													'<td id="" class="tikiListTableLabel tier1" data-trail="2_5" style="width:90px; text-align: right;">2.5</td>'.
													'<td class="tikiListTableItem">test</td>'.
												'</tr>'.
											'</table>'.
										'</td>'.
									'</tr>'.
									'<tr>'.
										'<td>'.
											'<table>'.
												'<tr>'.
													'<td id="" class="tikiListTableLabel tier1" data-trail="2_6" style="width:90px; text-align: right;">2.6</td>'.
													'<td class="tikiListTableItem">test</td>'.
												'</tr>'.
											'</table>'.
										'</td>'.
									'</tr>'.
								'</table>'.
							'</td>'.
						'</tr>'.
					'</table>'.
				'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>'.
					'<table>'.
						'<tr>'.
							'<td id="" class="tikiListTableLabel tier0" data-trail="3" style="width:60px; text-align: right;">3</td>'.
							'<td class="tikiListTableItem">test</td>'.
						'</tr>'.
					'</table>'.
				'</td>'.
			'</tr>'.
		'</table>'.
	'</div>');

		$parsed = $this->parser->parse($syntax[0]);

		$this->tryRemoveIdsFromHtmlList($parsed);

		$this->tryRemoveFingerprintId('html', $parsed);

		return array("parsed" => $parsed, "syntax" => $syntax);
	}
}

class WikiPlugin_injected extends WikiPlugin_HtmlBase
{
	public $type = "injected";
	public $np = false;

	public function output(&$data, &$params, &$index, &$parser)
	{
		return $data;
	}
}
