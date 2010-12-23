var TWParser = Editor.Parser = (function() {
	var tokenizeTW = (function() {
		function normal(source, setState, otherClass) {
			otherClass = (otherClass ? otherClass : "");
			
			var ch = source.next();
			switch (ch) {
				case "<": //comment
					if (source.lookAhead("!--", true)) {
						// Comment
						setState(inComment);
						return null;
					}
					break;
				case "_": //bold
					if (source.lookAhead("_", true)) {
						// Bold text
						setState(inBold);
						return null;
					} else {
						// Normal wikitext
						source.nextWhileMatches(/[^\n\[{<']/);
						return "tw-text";
					}
					break;
				case "'": //italics
					if (source.lookAhead("'", true)) {
						// Italic text
						setState(inItalic);
						return null;
					}
					else {
						// Normal wikitext
						source.nextWhileMatches(/[^\n\[{<']/);
						return "tw-text";
					}
					break;
				case "[":
					if (source.lookAhead("[", true)) {
						// Interwiki link
						setState(inLink);
						return null;
					}
					else {
						// Weblink
						setState(inWeblink);
						return null;
					}
					break;
				case "|": //table
					if (source.lookAhead("|", true)) {
						setState(inTable);
						return null;
					}
					else {
						// Normal wikitext
						source.nextWhileMatches(/[^\n\[{<']/);
						return "tw-text";
					}
					break;
				case "-": //titleBar
					if (source.lookAhead("=", true)) {
						setState(inTitleBar);
						return null;
					}
					else {
						// Normal wikitext
						source.nextWhileMatches(/[^\n\[{<']/);
						return "tw-text";
					}
					break;
				case "!": //header
					setState(inHeader);
					return null;
					break;
				case "*": //line item, or <li />
					setState(inListItem);
					return null;
					break;
				case "{": //plugin
					setState(inPluginContainer);
					return null;
					break;
				default:
					// Normal wikitext
					source.nextWhileMatches(/[^\n\[{<']/);
					return "tw-text";
					break;
			}
		}
		
		function inComment(source, setState) {
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "-" && source.lookAhead("->", true)) {
					setState(normal);
					break;
				}
			}
			return "tw-comment";
		}
		
		function inBold(source, setState) {
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "_" && source.lookAhead("_", true)) {
					setState(normal);
					break;
				}
			}
			return "tw-bold";
		}
		
		function inItalic(source, setState) {
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "'" && source.lookAhead("'", true)) {
					setState(normal);
					break;
				}
			}
			return "tw-italic";
		}
		
		function inLink(source, setState) {
			var closed = false;
			
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "]" && source.lookAhead("]", true)) {
					closed = true;
					break;
				}
			}
			
			setState(normal);
			if (closed) return "tw-link"; else return "tw-syntaxerror";
		}
		
		function inWeblink(source, setState) {
			var closed = false;
			
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "]") {
					closed = true;
					break;
				}
			}
			
			setState(normal);
			if (closed) return "tw-weblink"; else return "tw-syntaxerror";
		}
		
		function inTable(source, setState) {
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "|" && source.lookAhead("|", true)) {
					setState(normal);
					break;
				}
			}
			return "tw-table";
		}
		
		function inTitleBar(source, setState) {
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "=" && source.lookAhead("-", true)) {
					setState(normal);
					break;
				}
			}
			return "tw-titlebar";
		}
		
		function inHeader(source, setState) {
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "" || source.endOfLine()) {
					setState(normal);
					break;
				}
			}
			return "tw-header";
		}
		
		function inListItem(source, setState) {
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "" || source.endOfLine()) {
					setState(normal);
					break;
				}
			}
			return "tw-listitem";
		}
		
		function inPluginContainer(source, setState) {
			while (!source.endOfLine()) {
				var ch = source.next();
				if (ch == "}" || source.endOfLine()) {
					setState(normal);
					break;
				}
			}
			return "tw-plugin-container";
		}
		
		return function(source, startState) {
			return tokenizer(source, startState || normal);
		};
	})();
	
	function parseTW(source, space) {
		function indentTo(n) {return function() {return n;}}
		
		var tokens = tokenizeTW(source);		
		var space = space || 0;
		
		var iter = {
			next: function() {
		        var token = tokens.next(), style = token.style, content = token.content;
				if (content == "\n") {
					token.indentation = indentTo(space);
				}
				return token;
			},
			copy: function() {
				var _tokenState = tokens.state;
				return function(source) {
					tokens = tokenizeTW(source, _tokenState);
					return iter;
				};
			}
		};
		return iter;
	}
	return {make: parseTW};
})();
