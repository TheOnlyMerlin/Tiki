{remarksbox type="note" title="{tr}Note{/tr}"}
{tr}To use Google Maps, you must generate a Google Maps API Key for your web site. See <a href="http://www.google.com/apis/maps/signup.html">http://www.google.com/apis/maps/signup.html</a> for details.{/tr}
{/remarksbox}


<form action="tiki-admin.php?page=gmap" method="post">
	<input type="hidden" name="gmapsetup" value="" />
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" value="{tr}Change preferences{/tr}" />
	</div>

	<fieldset class="admin">
		<legend>{tr}Settings{/tr}</legend>
		<div class="adminoptionbox">
			<div class="adminoptionlabel"><label for="gmap_key">{tr}Google Maps API Key{/tr}: </label>{if $prefs.feature_help eq 'y'}{icon _id=help link="http://www.google.com/apis/maps/signup.html"}{/if}
				<br /><input type="text" name="gmap_key" id="gmap_key" value="{$prefs.gmap_key}" size="87" />
			</div>
		</div>
		<fieldset>
			<legend>{tr}Defaults{/tr}</legend>
			<div class="adminoptionbox">
				<div class="adminoptionlabel"><label for="gmap_defaultx">{tr}Default x for map center{/tr}:</label> <input type="text" name="gmap_defaultx" id="gmap_defaultx" value="{$prefs.gmap_defaultx}" /></div>
			</div>
			<div class="adminoptionbox">
				<div class="adminoptionlabel"><label for="gmap_defaulty">{tr}Default y from map center{/tr}:</label> <input type="text" name="gmap_defaulty" id="gmap_defaulty" value="{$prefs.gmap_defaulty}" /></div>
			</div>
			<div class="adminoptionbox">
				<div class="adminoptionlabel"><label for="gmap_defaultz">{tr}Default zoom level{/tr}:</label>
					<select name="gmap_defaultz" id="gmap_defaultz">
						<option value="1" {if $prefs.gmap_defaultz eq '1'}selected="selected"{/if}>1 - {tr}whole earth{/tr}</option>
						<option value="2" {if $prefs.gmap_defaultz eq '2'}selected="selected"{/if}>2</option>
						<option value="3" {if $prefs.gmap_defaultz eq '3'}selected="selected"{/if}>3</option>
						<option value="4" {if $prefs.gmap_defaultz eq '4'}selected="selected"{/if}>4</option>
						<option value="5" {if $prefs.gmap_defaultz eq '5'}selected="selected"{/if}>5 - {tr}country size{/tr}</option>
						<option value="6" {if $prefs.gmap_defaultz eq '6'}selected="selected"{/if}>6</option>
						<option value="7" {if $prefs.gmap_defaultz eq '7'}selected="selected"{/if}>7</option>
						<option value="8" {if $prefs.gmap_defaultz eq '8'}selected="selected"{/if}>8</option>
						<option value="9" {if $prefs.gmap_defaultz eq '9'}selected="selected"{/if}>9</option>
						<option value="10" {if $prefs.gmap_defaultz eq '10'}selected="selected"{/if}>10</option>
						<option value="11" {if $prefs.gmap_defaultz eq '11'}selected="selected"{/if}>11 - {tr}city size{/tr}</option>
						<option value="12" {if $prefs.gmap_defaultz eq '12'}selected="selected"{/if}>12</option>
						<option value="13" {if $prefs.gmap_defaultz eq '13'}selected="selected"{/if}>13</option>
						<option value="14" {if $prefs.gmap_defaultz eq '14'}selected="selected"{/if}>14</option>
						<option value="15" {if $prefs.gmap_defaultz eq '15'}selected="selected"{/if}>15</option>
						<option value="16" {if $prefs.gmap_defaultz eq '16'}selected="selected"{/if}>16</option>
						<option value="17" {if $prefs.gmap_defaultz eq '16'}selected="selected"{/if}>17</option>
						<option value="18" {if $prefs.gmap_defaultz eq '16'}selected="selected"{/if}>18</option>
						<option value="19" {if $prefs.gmap_defaultz eq '17'}selected="selected"{/if}>19 - {tr}max zoom{/tr}</option>
					</select>
				</div>
			</div>	
		</fieldset>	
	</fieldset>	
	<div class="heading input_submit_container" style="text-align: center">
		<input type="submit" value="{tr}Change preferences{/tr}" />
	</div>
</form>

{if $prefs.feature_gmap eq 'y' and $show_map eq 'y'}
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={$prefs.gmap_key}"></script>
<div class="wikitext">
	<div id="map" style="width: 500px; height: 400px;border: 1px solid #000;"></div>
</div>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
function load() {literal}{{/literal}
  var map = new GMap2(document.getElementById("map"));
  map.addControl(new GLargeMapControl());
  map.addControl(new GMapTypeControl());
  map.setCenter(new GLatLng({$prefs.gmap_defaulty}, {$prefs.gmap_defaultx}), {$prefs.gmap_defaultz});

  GEvent.addListener(map, "zoomend", function(gold, gnew) {literal}{{/literal}
    document.getElementById('gmap_defaultz').selectedIndex = gnew;
  {literal}});{/literal}

  GEvent.addListener(map, "moveend", function() {literal}{{/literal}
    document.getElementById('gmap_defaultx').value = map.getCenter().x;
    document.getElementById('gmap_defaulty').value = map.getCenter().y;
  {literal}});{/literal}

{literal}}{/literal}
//load();
//--><!]]>
window.onload=load;
</script>
{/if}
