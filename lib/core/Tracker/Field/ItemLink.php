<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Handler class for ItemLink
 * 
 * Letter key: ~r~
 *
 */
class Tracker_Field_ItemLink extends Tracker_Field_Abstract implements Tracker_Field_Synchronizable
{
	public static function getTypes()
	{
		return array(
			'r' => array(
				'name' => tr('Item Link'),
				'description' => tr('Link to an other item, similar to a foreign key.'),
				'help' => 'Items List and Item Link Tracker Fields',
				'prefs' => array('trackerfield_itemlink'),
				'tags' => array('advanced'),
				'default' => 'y',
				'params' => array(
					'trackerId' => array(
						'name' => tr('Tracker ID'),
						'description' => tr('Tracker to link to'),
						'filter' => 'int',
					),
					'fieldId' => array(
						'name' => tr('Field ID'),
						'description' => tr('Default field to display'),
						'filter' => 'int',
					),
					'linkToItem' => array(
						'name' => tr('Display'),
						'description' => tr('How the link to the item should be rendered'),
						'filter' => 'int',
						'options' => array(
							0 => tr('Value'),
							1 => tr('Link'),
						),
					),
					'displayFieldsList' => array(
						'name' => tr('Multiple Fields'),
						'description' => tr('Display the values from multiple fields instead of a single one.'),
						'separator' => '|',
						'filter' => 'int',
					),
					'status' => array(
						'name' => tr('Status Filter'),
						'description' => tr('Limit the available items to a selected set'),
						'filter' => 'alpha',
						'options' => array(
							'opc' => tr('all'),
							'o' => tr('open'),
							'p' => tr('pending'),
							'c' => tr('closed'),
							'op' => tr('open, pending'),
							'pc' => tr('pending, closed'),
						),
					),
					'linkPage' => array(
						'name' => tr('Link Page'),
						'description' => tr('Link to a wiki page instead of directly to the item'),
						'filter' => 'pagename',
					),
					'addItems' => array(
						'name' => tr('Add Items'),
						'description' => tr('Display text to allow new items to be added - e.g. "Add item..." (requires jQuery-UI)'),
						'filter' => 'text',
					),
					'addItemsWikiTpl' => array(
						'name' => tr('Add Item Template Page'),
						'description' => tr('Wiki page to use as a Pretty Tracker template'),
						'filter' => 'pagename',
					),
					'preSelectFieldHere' => array(
						'name' => tr('Preselect item based on value in this field'),
						'description' => tr('Preselect item based on value in specified field ID of item being edited'),
						'filter' => 'int',
					),
					'preSelectFieldThere' => array(
						'name' => tr('Preselect based on value in this remote field'),
						'description' => tr('Match preselect item with this field ID in tracker that is being linked to'),
						'filter' => 'int',
					),
					'preSelectFieldMethod' => array(
						'name' => tr('Preselection matching method'),
						'description' => tr('Method to use to match fields for preselection purposes'),
						'filter' => 'alpha',
						'options' => array(
							'exact' => tr('Exact Match'),
							'partial' => tr('Field here is part of field there'),
							'domain' => tr('Match domain, used for URL fields'),	
						),
					),
				),
			),
		);
	}

	function getFieldData(array $requestData = array())
	{
		$data = $this->getLinkData($requestData, $this->getInsertId());

		return $data;
	}

	function renderInput($context = array())
	{
		if ($this->getOption(6) && !$context['in_ajax_form']) {

			$context['in_ajax_form'] = true;
			
			require_once 'lib/wiki-plugins/wikiplugin_tracker.php';

			$params = array(
				'trackerId' => $this->getOption(0),
				'ignoreRequestItemId' => 'y',
				'_ajax_form_ins_id' => $this->getInsertId(),
			);

			if ($this->getOption(7)) {
				$params['wiki'] = $this->getOption(7);
			}
			$form = wikiplugin_tracker('', $params);

			$form = preg_replace(array('/<!--.*?-->/', '/\s+/', '/^~np~/', '/~\/np~/'), array('', ' ', '', ''), $form);	// remove comments etc

			TikiLib::lib('header')->add_jq_onready('
$("select[name=' . $this->getInsertId() . ']").change(function(e, val) {
	if ($(this).val() == -1) {
		var $d = $("<div id=\'add_dialog_' . $this->getInsertId() . '\' style=\'display:none\'>' . addslashes($form) . '</div>")
			.appendTo(document.body);
		
		var w = $d.width() * 1.4;
		var h = $d.height() * 2.0;
		if ($(document.body).width() < w) {
			w = $(document.body).width() * 0.8;
		}
		if ($(document.body).height() < h) {
			h = $(document.body).height() * 0.8;
		}

		$d.dialog({
				width: w,
				height: h,
				title: "'.$this->getOption(6).'",
				modal: true,
				buttons: {
					"Add": function() {
						var $f = $("form", this).append($("<input type=\'hidden\' name=\'ajax_add\' value=\'1\' />"));
						if (typeof $f.valid === "function" && $f.valid()) {
							ajaxLoadingShow($f);
							$.post( $f.attr("action"), $f.serialize(), function(data, status) {
								if (data && data.data) {
									for (var i = 0; i < data.data.length; i++) {
										var a = data.data[i];
										if ( a && a["fieldId"] == '.$this->getOption(1).' ) {
											$o = $("<option value=\'" + a["fileId"] + "\'>" + a["value"] + "</option>");
											$("select[name=' . $this->getInsertId() . '] > option:first").after($o);
											$("select[name=' . $this->getInsertId() . ']")[0].selectedIndex = 1;
										}
									}
								}
								ajaxLoadingHide();
								$d.dialog( "close" );

								return;
							}, "json");
						}

						//.append($("<input type=\'hidden\' name=\'save\' value=\'save\' />"))
						//.submit();
					},
					Cancel: function() {
						$("select[name=' . $this->getInsertId() . ']")[0].selectedIndex = 0;
						$( this ).dialog( "close" );
					}
				},
				create: function(event, ui) {
					 ajaxTrackerFormInit_' . $this->getInsertId() . '();
				}
			}).find(".input_submit_container").remove();
	}
});
');

		}

		if ($preselection = $this->getPreselection()) {
			$context['preselection'] = $preselection;
		} else {
			$context['preselection'] = '';
		}

		return $this->renderTemplate('trackerinput/itemlink.tpl', $context);
	}

	function renderOutput($context = array())
	{
		$smarty = TikiLib::lib('smarty');

		$item = $this->getConfiguration('value');
		$dlist = $this->getConfiguration('listdisplay');
		$list = $this->getConfiguration('list');
		if (!empty($dlist)) {
			$label = isset($dlist[$item]) ? $dlist[$item] : '';
		} else {
			$label = isset($list[$item]) ? $list[$item] : '';
		}
		if ($item && $context['list_mode'] !== 'csv' && $this->getOption(2)) {
			$smarty->loadPlugin('smarty_function_object_link');

			return smarty_function_object_link(array(
				'type' => 'trackeritem',
				'id' => $item,
				'title' => $label,
			), $smarty);
		} elseif ($label) {
			return $label;
		}
	}

		function getDocumentPart($baseKey, Search_Type_Factory_Interface $typeFactory)
	{
		$data = $this->getLinkData(array(), 0);
		$item = $data['value']; 
		$dlist = $data['listdisplay'];
		$list = $data['list'];

		if (!empty($dlist)) {
			$label = isset($dlist[$item]) ? $dlist[$item] : '';
		} else {
			$label = isset($list[$item]) ? $list[$item] : '';
		}
		return array(
			$baseKey => $typeFactory->sortable($item),
			"{$baseKey}_text" => $typeFactory->plaintext($label),
		);
	}

	function getProvidedFields($baseKey)
        {
		return array(
			$baseKey,
			"{$baseKey}_text",
		);
	}

	function getGlobalFields($baseKey)
	{
		return array();
	}

	private function getLinkData($requestData, $string_id)
	{
		$data = array(
			'value' => isset($requestData[$string_id]) ? $requestData[$string_id] : $this->getValue(),
		);

		if (!$this->getOption(3)) {	//no displayedFieldsList
			$data['list'] = TikiLib::lib('trk')->get_all_items(
				$this->getOption(0),
				$this->getOption(1),
				$this->getOption(4, 'opc'),
				false
			);
			if (array_unique($data['list']) != $data['list']) {
				$newlist = array();
				foreach($data['list'] as $k => $dl) {
					if (in_array($dl, $newlist)) {
						$dl = $dl . " ($k)";
					}
					$newlist[$k] = $dl;
				}
				$data['list'] = $newlist;
			}
		} else {
			$data['list'] = TikiLib::lib('trk')->get_all_items(
				$this->getOption(0),
				$this->getOption(1),
				$this->getOption(4, 'opc'),
				false
			);
			$data['listdisplay'] = array_unique(
				TikiLib::lib('trk')->concat_all_items_from_fieldslist(
					$this->getOption(0),
					$this->getOption(3),
					$this->getOption(4, 'opc')
				)
			);
			if (array_unique($data['listdisplay']) != $data['listdisplay']) {
				$newlist = array();
				foreach($data['listdisplay'] as $k => $dl) {
					if (in_array($dl, $newlist)) {
						$dl = $dl . " ($k)";
					}
					$newlist[$k] = $dl;
				}
				$data['listdisplay'] = $newlist;
			}
		}

		if ($this->getOption(6)) {	// addItems
			$data['list']['-1'] = $this->getOption(6);
			if (isset($data['listdisplay'])) {
				$data['listdisplay']['-1'] = $this->getOption(6);
			}
		}
		
		return $data;
	}

	function importRemote($value)
	{
		return $value;
	}

	function exportRemote($value)
	{
		return $value;
	}

	function importRemoteField(array $info, array $syncInfo)
	{
		$sourceOptions = explode(',', $info['options']);
		$trackerId = isset($sourceOptions[0]) ? (int) $sourceOptions[0] : 0;
		$fieldId = isset($sourceOptions[1]) ? (int) $sourceOptions[1] : 0;
		$status = isset($sourceOptions[4]) ? (int) $sourceOptions[4] : 'opc';

		$info['type'] = 'd';
		$info['options'] = $this->getRemoteItemLinks($syncInfo, $trackerId, $fieldId, $status);

		return $info;
	}

	private function getRemoteItemLinks($syncInfo, $trackerId, $fieldId, $status)
	{
		$controller = new Services_RemoteController($syncInfo['provider'], 'tracker');
		$items = $controller->getResultLoader('list_items', array(
			'trackerId' => $trackerId,
			'status' => $status,
		));
		$result = $controller->edit_field(array(
			'trackerId' => $trackerId,
			'fieldId' => $fieldId,
		));

		$permName = $result['field']['permName'];
		if (empty($permName)) {
			return '';
		}

		$parts = array();
		foreach ($items as $item) {
			$parts[] = $item['itemId'] . '=' . $item['fields'][$permName];
		}

		return implode(',', $parts);
	}

	private function getPreselection()
	{
		$trklib = TikiLib::lib('trk');

		$localField = $this->getOption(8);
		$remoteField = $this->getOption(9);
		$method = $this->getOption(10);
		$localTrackerId = $this->getConfiguration('trackerId');
                $remoteTrackerId = $this->getOption(0);

            	$localValue = $trklib->get_item_value($localTrackerId, $this->getItemId(), $localField);

		if ($method == 'domain') {
			if (! preg_match('@^(?:http://)?([^/]+)@i', $localValue, $matches)) {
				return '';
			}
			$host = $matches[1];
			preg_match('/[^.]+\.[^.]+$/', $host, $matches);
			$domain = $matches[0];	
			if (strlen($domain) > 6) {
				// avoid com.sg or similar country subdomains
				$localValue = $domain;
			} else {
				$localValue = $host;
			}
		} 

		if ($method == 'domain' || $method == 'partial') {
			$partial = true;
		} else {
			$partial = false;
		}

		return $trklib->get_item_id($remoteTrackerId, $remoteField, $localValue, $partial);
	}
}

