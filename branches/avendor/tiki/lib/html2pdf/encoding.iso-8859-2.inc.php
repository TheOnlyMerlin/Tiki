<?php
// $Header: /cvsroot/tikiwiki/tiki/lib/html2pdf/encoding.iso-8859-2.inc.php,v 1.1.1.1 2006-02-08 11:02:02 nikchankov Exp $

$g_iso_8859_2 = array(
                      "\x00" => code_to_utf8(0x0000),	//	NULL
                      "\x01" => code_to_utf8(0x0001),	//	START OF HEADING
                      "\x02" => code_to_utf8(0x0002),	//	START OF TEXT
                      "\x03" => code_to_utf8(0x0003),	//	END OF TEXT
                      "\x04" => code_to_utf8(0x0004),	//	END OF TRANSMISSION
                      "\x05" => code_to_utf8(0x0005),	//	ENQUIRY
                      "\x06" => code_to_utf8(0x0006),	//	ACKNOWLEDGE
                      "\x07" => code_to_utf8(0x0007),	//	BELL
                      "\x08" => code_to_utf8(0x0008),	//	BACKSPACE
                      "\x09" => code_to_utf8(0x0009),	//	HORIZONTAL TABULATION
                      "\x0A" => code_to_utf8(0x000A),	//	LINE FEED
                      "\x0B" => code_to_utf8(0x000B),	//	VERTICAL TABULATION
                      "\x0C" => code_to_utf8(0x000C),	//	FORM FEED
                      "\x0D" => code_to_utf8(0x000D),	//	CARRIAGE RETURN
                      "\x0E" => code_to_utf8(0x000E),	//	SHIFT OUT
                      "\x0F" => code_to_utf8(0x000F),	//	SHIFT IN
                      "\x10" => code_to_utf8(0x0010),	//	DATA LINK ESCAPE
                      "\x11" => code_to_utf8(0x0011),	//	DEVICE CONTROL ONE
                      "\x12" => code_to_utf8(0x0012),	//	DEVICE CONTROL TWO
                      "\x13" => code_to_utf8(0x0013),	//	DEVICE CONTROL THREE
                      "\x14" => code_to_utf8(0x0014),	//	DEVICE CONTROL FOUR
                      "\x15" => code_to_utf8(0x0015),	//	NEGATIVE ACKNOWLEDGE
                      "\x16" => code_to_utf8(0x0016),	//	SYNCHRONOUS IDLE
                      "\x17" => code_to_utf8(0x0017),	//	END OF TRANSMISSION BLOCK
                      "\x18" => code_to_utf8(0x0018),	//	CANCEL
                      "\x19" => code_to_utf8(0x0019),	//	END OF MEDIUM
                      "\x1A" => code_to_utf8(0x001A),	//	SUBSTITUTE
                      "\x1B" => code_to_utf8(0x001B),	//	ESCAPE
                      "\x1C" => code_to_utf8(0x001C),	//	FILE SEPARATOR
                      "\x1D" => code_to_utf8(0x001D),	//	GROUP SEPARATOR
                      "\x1E" => code_to_utf8(0x001E),	//	RECORD SEPARATOR
                      "\x1F" => code_to_utf8(0x001F),	//	UNIT SEPARATOR
                      "\x20" => code_to_utf8(0x0020),	//	SPACE
                      "\x21" => code_to_utf8(0x0021),	//	EXCLAMATION MARK
                      "\x22" => code_to_utf8(0x0022),	//	QUOTATION MARK
                      "\x23" => code_to_utf8(0x0023),	//	NUMBER SIGN
                      "\x24" => code_to_utf8(0x0024),	//	DOLLAR SIGN
                      "\x25" => code_to_utf8(0x0025),	//	PERCENT SIGN
                      "\x26" => code_to_utf8(0x0026),	//	AMPERSAND
                      "\x27" => code_to_utf8(0x0027),	//	APOSTROPHE
                      "\x28" => code_to_utf8(0x0028),	//	LEFT PARENTHESIS
                      "\x29" => code_to_utf8(0x0029),	//	RIGHT PARENTHESIS
                      "\x2A" => code_to_utf8(0x002A),	//	ASTERISK
                      "\x2B" => code_to_utf8(0x002B),	//	PLUS SIGN
                      "\x2C" => code_to_utf8(0x002C),	//	COMMA
                      "\x2D" => code_to_utf8(0x002D),	//	HYPHEN-MINUS
                      "\x2E" => code_to_utf8(0x002E),	//	FULL STOP
                      "\x2F" => code_to_utf8(0x002F),	//	SOLIDUS
                      "\x30" => code_to_utf8(0x0030),	//	DIGIT ZERO
                      "\x31" => code_to_utf8(0x0031),	//	DIGIT ONE
                      "\x32" => code_to_utf8(0x0032),	//	DIGIT TWO
                      "\x33" => code_to_utf8(0x0033),	//	DIGIT THREE
                      "\x34" => code_to_utf8(0x0034),	//	DIGIT FOUR
                      "\x35" => code_to_utf8(0x0035),	//	DIGIT FIVE
                      "\x36" => code_to_utf8(0x0036),	//	DIGIT SIX
                      "\x37" => code_to_utf8(0x0037),	//	DIGIT SEVEN
                      "\x38" => code_to_utf8(0x0038),	//	DIGIT EIGHT
                      "\x39" => code_to_utf8(0x0039),	//	DIGIT NINE
                      "\x3A" => code_to_utf8(0x003A),	//	COLON
                      "\x3B" => code_to_utf8(0x003B),	//	SEMICOLON
                      "\x3C" => code_to_utf8(0x003C),	//	LESS-THAN SIGN
                      "\x3D" => code_to_utf8(0x003D),	//	EQUALS SIGN
                      "\x3E" => code_to_utf8(0x003E),	//	GREATER-THAN SIGN
                      "\x3F" => code_to_utf8(0x003F),	//	QUESTION MARK
                      "\x40" => code_to_utf8(0x0040),	//	COMMERCIAL AT
                      "\x41" => code_to_utf8(0x0041),	//	LATIN CAPITAL LETTER A
                      "\x42" => code_to_utf8(0x0042),	//	LATIN CAPITAL LETTER B
                      "\x43" => code_to_utf8(0x0043),	//	LATIN CAPITAL LETTER C
                      "\x44" => code_to_utf8(0x0044),	//	LATIN CAPITAL LETTER D
                      "\x45" => code_to_utf8(0x0045),	//	LATIN CAPITAL LETTER E
                      "\x46" => code_to_utf8(0x0046),	//	LATIN CAPITAL LETTER F
                      "\x47" => code_to_utf8(0x0047),	//	LATIN CAPITAL LETTER G
                      "\x48" => code_to_utf8(0x0048),	//	LATIN CAPITAL LETTER H
                      "\x49" => code_to_utf8(0x0049),	//	LATIN CAPITAL LETTER I
                      "\x4A" => code_to_utf8(0x004A),	//	LATIN CAPITAL LETTER J
                      "\x4B" => code_to_utf8(0x004B),	//	LATIN CAPITAL LETTER K
                      "\x4C" => code_to_utf8(0x004C),	//	LATIN CAPITAL LETTER L
                      "\x4D" => code_to_utf8(0x004D),	//	LATIN CAPITAL LETTER M
                      "\x4E" => code_to_utf8(0x004E),	//	LATIN CAPITAL LETTER N
                      "\x4F" => code_to_utf8(0x004F),	//	LATIN CAPITAL LETTER O
                      "\x50" => code_to_utf8(0x0050),	//	LATIN CAPITAL LETTER P
                      "\x51" => code_to_utf8(0x0051),	//	LATIN CAPITAL LETTER Q
                      "\x52" => code_to_utf8(0x0052),	//	LATIN CAPITAL LETTER R
                      "\x53" => code_to_utf8(0x0053),	//	LATIN CAPITAL LETTER S
                      "\x54" => code_to_utf8(0x0054),	//	LATIN CAPITAL LETTER T
                      "\x55" => code_to_utf8(0x0055),	//	LATIN CAPITAL LETTER U
                      "\x56" => code_to_utf8(0x0056),	//	LATIN CAPITAL LETTER V
                      "\x57" => code_to_utf8(0x0057),	//	LATIN CAPITAL LETTER W
                      "\x58" => code_to_utf8(0x0058),	//	LATIN CAPITAL LETTER X
                      "\x59" => code_to_utf8(0x0059),	//	LATIN CAPITAL LETTER Y
                      "\x5A" => code_to_utf8(0x005A),	//	LATIN CAPITAL LETTER Z
                      "\x5B" => code_to_utf8(0x005B),	//	LEFT SQUARE BRACKET
                      "\x5C" => code_to_utf8(0x005C),	//	REVERSE SOLIDUS
                      "\x5D" => code_to_utf8(0x005D),	//	RIGHT SQUARE BRACKET
                      "\x5E" => code_to_utf8(0x005E),	//	CIRCUMFLEX ACCENT
                      "\x5F" => code_to_utf8(0x005F),	//	LOW LINE
                      "\x60" => code_to_utf8(0x0060),	//	GRAVE ACCENT
                      "\x61" => code_to_utf8(0x0061),	//	LATIN SMALL LETTER A
                      "\x62" => code_to_utf8(0x0062),	//	LATIN SMALL LETTER B
                      "\x63" => code_to_utf8(0x0063),	//	LATIN SMALL LETTER C
                      "\x64" => code_to_utf8(0x0064),	//	LATIN SMALL LETTER D
                      "\x65" => code_to_utf8(0x0065),	//	LATIN SMALL LETTER E
                      "\x66" => code_to_utf8(0x0066),	//	LATIN SMALL LETTER F
                      "\x67" => code_to_utf8(0x0067),	//	LATIN SMALL LETTER G
                      "\x68" => code_to_utf8(0x0068),	//	LATIN SMALL LETTER H
                      "\x69" => code_to_utf8(0x0069),	//	LATIN SMALL LETTER I
                      "\x6A" => code_to_utf8(0x006A),	//	LATIN SMALL LETTER J
                      "\x6B" => code_to_utf8(0x006B),	//	LATIN SMALL LETTER K
                      "\x6C" => code_to_utf8(0x006C),	//	LATIN SMALL LETTER L
                      "\x6D" => code_to_utf8(0x006D),	//	LATIN SMALL LETTER M
                      "\x6E" => code_to_utf8(0x006E),	//	LATIN SMALL LETTER N
                      "\x6F" => code_to_utf8(0x006F),	//	LATIN SMALL LETTER O
                      "\x70" => code_to_utf8(0x0070),	//	LATIN SMALL LETTER P
                      "\x71" => code_to_utf8(0x0071),	//	LATIN SMALL LETTER Q
                      "\x72" => code_to_utf8(0x0072),	//	LATIN SMALL LETTER R
                      "\x73" => code_to_utf8(0x0073),	//	LATIN SMALL LETTER S
                      "\x74" => code_to_utf8(0x0074),	//	LATIN SMALL LETTER T
                      "\x75" => code_to_utf8(0x0075),	//	LATIN SMALL LETTER U
                      "\x76" => code_to_utf8(0x0076),	//	LATIN SMALL LETTER V
                      "\x77" => code_to_utf8(0x0077),	//	LATIN SMALL LETTER W
                      "\x78" => code_to_utf8(0x0078),	//	LATIN SMALL LETTER X
                      "\x79" => code_to_utf8(0x0079),	//	LATIN SMALL LETTER Y
                      "\x7A" => code_to_utf8(0x007A),	//	LATIN SMALL LETTER Z
                      "\x7B" => code_to_utf8(0x007B),	//	LEFT CURLY BRACKET
                      "\x7C" => code_to_utf8(0x007C),	//	VERTICAL LINE
                      "\x7D" => code_to_utf8(0x007D),	//	RIGHT CURLY BRACKET
                      "\x7E" => code_to_utf8(0x007E),	//	TILDE
                      "\x7F" => code_to_utf8(0x007F),	//	DELETE
                      "\x80" => code_to_utf8(0x0080),	//	<control>
                      "\x81" => code_to_utf8(0x0081),	//	<control>
                      "\x82" => code_to_utf8(0x0082),	//	<control>
                      "\x83" => code_to_utf8(0x0083),	//	<control>
                      "\x84" => code_to_utf8(0x0084),	//	<control>
                      "\x85" => code_to_utf8(0x0085),	//	<control>
                      "\x86" => code_to_utf8(0x0086),	//	<control>
                      "\x87" => code_to_utf8(0x0087),	//	<control>
                      "\x88" => code_to_utf8(0x0088),	//	<control>
                      "\x89" => code_to_utf8(0x0089),	//	<control>
                      "\x8A" => code_to_utf8(0x008A),	//	<control>
                      "\x8B" => code_to_utf8(0x008B),	//	<control>
                      "\x8C" => code_to_utf8(0x008C),	//	<control>
                      "\x8D" => code_to_utf8(0x008D),	//	<control>
                      "\x8E" => code_to_utf8(0x008E),	//	<control>
                      "\x8F" => code_to_utf8(0x008F),	//	<control>
                      "\x90" => code_to_utf8(0x0090),	//	<control>
                      "\x91" => code_to_utf8(0x0091),	//	<control>
                      "\x92" => code_to_utf8(0x0092),	//	<control>
                      "\x93" => code_to_utf8(0x0093),	//	<control>
                      "\x94" => code_to_utf8(0x0094),	//	<control>
                      "\x95" => code_to_utf8(0x0095),	//	<control>
                      "\x96" => code_to_utf8(0x0096),	//	<control>
                      "\x97" => code_to_utf8(0x0097),	//	<control>
                      "\x98" => code_to_utf8(0x0098),	//	<control>
                      "\x99" => code_to_utf8(0x0099),	//	<control>
                      "\x9A" => code_to_utf8(0x009A),	//	<control>
                      "\x9B" => code_to_utf8(0x009B),	//	<control>
                      "\x9C" => code_to_utf8(0x009C),	//	<control>
                      "\x9D" => code_to_utf8(0x009D),	//	<control>
                      "\x9E" => code_to_utf8(0x009E),	//	<control>
                      "\x9F" => code_to_utf8(0x009F),	//	<control>
                      "\xA0" => code_to_utf8(0x00A0),	//	NO-BREAK SPACE
                      "\xA1" => code_to_utf8(0x0104),	//	LATIN CAPITAL LETTER A WITH OGONEK
                      "\xA2" => code_to_utf8(0x02D8),	//	BREVE
                      "\xA3" => code_to_utf8(0x0141),	//	LATIN CAPITAL LETTER L WITH STROKE
                      "\xA4" => code_to_utf8(0x00A4),	//	CURRENCY SIGN
                      "\xA5" => code_to_utf8(0x013D),	//	LATIN CAPITAL LETTER L WITH CARON
                      "\xA6" => code_to_utf8(0x015A),	//	LATIN CAPITAL LETTER S WITH ACUTE
                      "\xA7" => code_to_utf8(0x00A7),	//	SECTION SIGN
                      "\xA8" => code_to_utf8(0x00A8),	//	DIAERESIS
                      "\xA9" => code_to_utf8(0x0160),	//	LATIN CAPITAL LETTER S WITH CARON
                      "\xAA" => code_to_utf8(0x015E),	//	LATIN CAPITAL LETTER S WITH CEDILLA
                      "\xAB" => code_to_utf8(0x0164),	//	LATIN CAPITAL LETTER T WITH CARON
                      "\xAC" => code_to_utf8(0x0179),	//	LATIN CAPITAL LETTER Z WITH ACUTE
                      "\xAD" => code_to_utf8(0x00AD),	//	SOFT HYPHEN
                      "\xAE" => code_to_utf8(0x017D),	//	LATIN CAPITAL LETTER Z WITH CARON
                      "\xAF" => code_to_utf8(0x017B),	//	LATIN CAPITAL LETTER Z WITH DOT ABOVE
                      "\xB0" => code_to_utf8(0x00B0),	//	DEGREE SIGN
                      "\xB1" => code_to_utf8(0x0105),	//	LATIN SMALL LETTER A WITH OGONEK
                      "\xB2" => code_to_utf8(0x02DB),	//	OGONEK
                      "\xB3" => code_to_utf8(0x0142),	//	LATIN SMALL LETTER L WITH STROKE
                      "\xB4" => code_to_utf8(0x00B4),	//	ACUTE ACCENT
                      "\xB5" => code_to_utf8(0x013E),	//	LATIN SMALL LETTER L WITH CARON
                      "\xB6" => code_to_utf8(0x015B),	//	LATIN SMALL LETTER S WITH ACUTE
                      "\xB7" => code_to_utf8(0x02C7),	//	CARON
                      "\xB8" => code_to_utf8(0x00B8),	//	CEDILLA
                      "\xB9" => code_to_utf8(0x0161),	//	LATIN SMALL LETTER S WITH CARON
                      "\xBA" => code_to_utf8(0x015F),	//	LATIN SMALL LETTER S WITH CEDILLA
                      "\xBB" => code_to_utf8(0x0165),	//	LATIN SMALL LETTER T WITH CARON
                      "\xBC" => code_to_utf8(0x017A),	//	LATIN SMALL LETTER Z WITH ACUTE
                      "\xBD" => code_to_utf8(0x02DD),	//	DOUBLE ACUTE ACCENT
                      "\xBE" => code_to_utf8(0x017E),	//	LATIN SMALL LETTER Z WITH CARON
                      "\xBF" => code_to_utf8(0x017C),	//	LATIN SMALL LETTER Z WITH DOT ABOVE
                      "\xC0" => code_to_utf8(0x0154),	//	LATIN CAPITAL LETTER R WITH ACUTE
                      "\xC1" => code_to_utf8(0x00C1),	//	LATIN CAPITAL LETTER A WITH ACUTE
                      "\xC2" => code_to_utf8(0x00C2),	//	LATIN CAPITAL LETTER A WITH CIRCUMFLEX
                      "\xC3" => code_to_utf8(0x0102),	//	LATIN CAPITAL LETTER A WITH BREVE
                      "\xC4" => code_to_utf8(0x00C4),	//	LATIN CAPITAL LETTER A WITH DIAERESIS
                      "\xC5" => code_to_utf8(0x0139),	//	LATIN CAPITAL LETTER L WITH ACUTE
                      "\xC6" => code_to_utf8(0x0106),	//	LATIN CAPITAL LETTER C WITH ACUTE
                      "\xC7" => code_to_utf8(0x00C7),	//	LATIN CAPITAL LETTER C WITH CEDILLA
                      "\xC8" => code_to_utf8(0x010C),	//	LATIN CAPITAL LETTER C WITH CARON
                      "\xC9" => code_to_utf8(0x00C9),	//	LATIN CAPITAL LETTER E WITH ACUTE
                      "\xCA" => code_to_utf8(0x0118),	//	LATIN CAPITAL LETTER E WITH OGONEK
                      "\xCB" => code_to_utf8(0x00CB),	//	LATIN CAPITAL LETTER E WITH DIAERESIS
                      "\xCC" => code_to_utf8(0x011A),	//	LATIN CAPITAL LETTER E WITH CARON
                      "\xCD" => code_to_utf8(0x00CD),	//	LATIN CAPITAL LETTER I WITH ACUTE
                      "\xCE" => code_to_utf8(0x00CE),	//	LATIN CAPITAL LETTER I WITH CIRCUMFLEX
                      "\xCF" => code_to_utf8(0x010E),	//	LATIN CAPITAL LETTER D WITH CARON
                      "\xD0" => code_to_utf8(0x0110),	//	LATIN CAPITAL LETTER D WITH STROKE
                      "\xD1" => code_to_utf8(0x0143),	//	LATIN CAPITAL LETTER N WITH ACUTE
                      "\xD2" => code_to_utf8(0x0147),	//	LATIN CAPITAL LETTER N WITH CARON
                      "\xD3" => code_to_utf8(0x00D3),	//	LATIN CAPITAL LETTER O WITH ACUTE
                      "\xD4" => code_to_utf8(0x00D4),	//	LATIN CAPITAL LETTER O WITH CIRCUMFLEX
                      "\xD5" => code_to_utf8(0x0150),	//	LATIN CAPITAL LETTER O WITH DOUBLE ACUTE
                      "\xD6" => code_to_utf8(0x00D6),	//	LATIN CAPITAL LETTER O WITH DIAERESIS
                      "\xD7" => code_to_utf8(0x00D7),	//	MULTIPLICATION SIGN
                      "\xD8" => code_to_utf8(0x0158),	//	LATIN CAPITAL LETTER R WITH CARON
                      "\xD9" => code_to_utf8(0x016E),	//	LATIN CAPITAL LETTER U WITH RING ABOVE
                      "\xDA" => code_to_utf8(0x00DA),	//	LATIN CAPITAL LETTER U WITH ACUTE
                      "\xDB" => code_to_utf8(0x0170),	//	LATIN CAPITAL LETTER U WITH DOUBLE ACUTE
                      "\xDC" => code_to_utf8(0x00DC),	//	LATIN CAPITAL LETTER U WITH DIAERESIS
                      "\xDD" => code_to_utf8(0x00DD),	//	LATIN CAPITAL LETTER Y WITH ACUTE
                      "\xDE" => code_to_utf8(0x0162),	//	LATIN CAPITAL LETTER T WITH CEDILLA
                      "\xDF" => code_to_utf8(0x00DF),	//	LATIN SMALL LETTER SHARP S
                      "\xE0" => code_to_utf8(0x0155),	//	LATIN SMALL LETTER R WITH ACUTE
                      "\xE1" => code_to_utf8(0x00E1),	//	LATIN SMALL LETTER A WITH ACUTE
                      "\xE2" => code_to_utf8(0x00E2),	//	LATIN SMALL LETTER A WITH CIRCUMFLEX
                      "\xE3" => code_to_utf8(0x0103),	//	LATIN SMALL LETTER A WITH BREVE
                      "\xE4" => code_to_utf8(0x00E4),	//	LATIN SMALL LETTER A WITH DIAERESIS
                      "\xE5" => code_to_utf8(0x013A),	//	LATIN SMALL LETTER L WITH ACUTE
                      "\xE6" => code_to_utf8(0x0107),	//	LATIN SMALL LETTER C WITH ACUTE
                      "\xE7" => code_to_utf8(0x00E7),	//	LATIN SMALL LETTER C WITH CEDILLA
                      "\xE8" => code_to_utf8(0x010D),	//	LATIN SMALL LETTER C WITH CARON
                      "\xE9" => code_to_utf8(0x00E9),	//	LATIN SMALL LETTER E WITH ACUTE
                      "\xEA" => code_to_utf8(0x0119),	//	LATIN SMALL LETTER E WITH OGONEK
                      "\xEB" => code_to_utf8(0x00EB),	//	LATIN SMALL LETTER E WITH DIAERESIS
                      "\xEC" => code_to_utf8(0x011B),	//	LATIN SMALL LETTER E WITH CARON
                      "\xED" => code_to_utf8(0x00ED),	//	LATIN SMALL LETTER I WITH ACUTE
                      "\xEE" => code_to_utf8(0x00EE),	//	LATIN SMALL LETTER I WITH CIRCUMFLEX
                      "\xEF" => code_to_utf8(0x010F),	//	LATIN SMALL LETTER D WITH CARON
                      "\xF0" => code_to_utf8(0x0111),	//	LATIN SMALL LETTER D WITH STROKE
                      "\xF1" => code_to_utf8(0x0144),	//	LATIN SMALL LETTER N WITH ACUTE
                      "\xF2" => code_to_utf8(0x0148),	//	LATIN SMALL LETTER N WITH CARON
                      "\xF3" => code_to_utf8(0x00F3),	//	LATIN SMALL LETTER O WITH ACUTE
                      "\xF4" => code_to_utf8(0x00F4),	//	LATIN SMALL LETTER O WITH CIRCUMFLEX
                      "\xF5" => code_to_utf8(0x0151),	//	LATIN SMALL LETTER O WITH DOUBLE ACUTE
                      "\xF6" => code_to_utf8(0x00F6),	//	LATIN SMALL LETTER O WITH DIAERESIS
                      "\xF7" => code_to_utf8(0x00F7),	//	DIVISION SIGN
                      "\xF8" => code_to_utf8(0x0159),	//	LATIN SMALL LETTER R WITH CARON
                      "\xF9" => code_to_utf8(0x016F),	//	LATIN SMALL LETTER U WITH RING ABOVE
                      "\xFA" => code_to_utf8(0x00FA),	//	LATIN SMALL LETTER U WITH ACUTE
                      "\xFB" => code_to_utf8(0x0171),	//	LATIN SMALL LETTER U WITH DOUBLE ACUTE
                      "\xFC" => code_to_utf8(0x00FC),	//	LATIN SMALL LETTER U WITH DIAERESIS
                      "\xFD" => code_to_utf8(0x00FD),	//	LATIN SMALL LETTER Y WITH ACUTE
                      "\xFE" => code_to_utf8(0x0163),	//	LATIN SMALL LETTER T WITH CEDILLA
                      "\xFF" => code_to_utf8(0x02D9)	//	DOT ABOVE 
                      );
?>