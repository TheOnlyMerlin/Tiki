var TWParser = Editor.Parser = (function() {
	var tokenizeTW = (function() {
		function normal(source, setState) {
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
				case "{":
					if (source.lookAhead("|", true)) {
						// Table
						setState(inTable);
						return null;
					}
					else {
						// Normal wikitext
						source.nextWhileMatches(/[^\n\[{<']/);
						return "tw-text";
					}
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
				if (ch == "|" && source.lookAhead("}", true)) {
					setState(normal);
					break;
				}
			}
			return "tw-table";
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
