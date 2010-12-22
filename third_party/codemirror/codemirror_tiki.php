<?php

function tiki_syntax_highlighter_base() {
	global $headerlib;
	
	$headerlib->add_cssfile( 'lib/codemirror_tiki/docs.css' );
	$headerlib->add_jsfile( 'lib/codemirror_tiki/js/codemirror.js' );
	
	$headerlib->add_js("
		var editwiki = $('#editwiki');
		//ensure that codemirror is running, if so run
		if (CodeMirror) {
			var editor = CodeMirror.fromTextArea('editwiki', {
				height: '350px',
				parserfile: ['parsetikisyntax.js'],
				stylesheet: ['lib/codemirror_tiki/css/tikiwikisyntaxcolors.css'],
				path: 'lib/codemirror_tiki/js/',
				onChange: function() {
					//Setup codemirror to send the text back to the textarea
					editwiki.val(editor.getCode());
				}
			});
		}
	");
}