<?php
// bashscript to create mimetypes.php:
// echo "\$mimetypes=Array("
// for ((i=2;i<9;i++)) ; do
//  egrep -v "^#" /etc/mime.types |awk '{if ($'$i'){print "\"" $'$i' "\" => \"" $1 "\","}}' >> mimetypes.php.new
// done
// echo ");"
static $mimetypes=Array(
"ez" => "application/andrew-inset",
"hqx" => "application/mac-binhex40",
"cpt" => "application/mac-compactpro",
"doc" => "application/msword",
"bin" => "application/octet-stream",
"oda" => "application/oda",
"pdf" => "application/pdf",
"ai" => "application/postscript",
"rtf" => "application/rtf",
"smi" => "application/smil",
"mif" => "application/vnd.mif",
"ppt" => "application/vnd.ms-powerpoint",
"bcpio" => "application/x-bcpio",
"vcd" => "application/x-cdlink",
"pgn" => "application/x-chess-pgn",
"cpio" => "application/x-cpio",
"csh" => "application/x-csh",
"dcr" => "application/x-director",
"dvi" => "application/x-dvi",
"spl" => "application/x-futuresplash",
"gtar" => "application/x-gtar",
"hdf" => "application/x-hdf",
"js" => "application/x-javascript",
"skp" => "application/x-koan",
"latex" => "application/x-latex",
"nc" => "application/x-netcdf",
"sh" => "application/x-sh",
"shar" => "application/x-shar",
"swf" => "application/x-shockwave-flash",
"sit" => "application/x-stuffit",
"sv4cpio" => "application/x-sv4cpio",
"sv4crc" => "application/x-sv4crc",
"tar" => "application/x-tar",
"tcl" => "application/x-tcl",
"tex" => "application/x-tex",
"texinfo" => "application/x-texinfo",
"t" => "application/x-troff",
"man" => "application/x-troff-man",
"me" => "application/x-troff-me",
"ms" => "application/x-troff-ms",
"ustar" => "application/x-ustar",
"src" => "application/x-wais-source",
"zip" => "application/zip",
"au" => "audio/basic",
"mid" => "audio/midi",
"mpga" => "audio/mpeg",
"aif" => "audio/x-aiff",
"ram" => "audio/x-pn-realaudio",
"rpm" => "audio/x-pn-realaudio-plugin",
"ra" => "audio/x-realaudio",
"wav" => "audio/x-wav",
"pdb" => "chemical/x-pdb",
"gif" => "image/gif",
"ief" => "image/ief",
"jpeg" => "image/jpeg",
"png" => "image/png",
"tiff" => "image/tiff",
"ras" => "image/x-cmu-raster",
"pnm" => "image/x-portable-anymap",
"pbm" => "image/x-portable-bitmap",
"pgm" => "image/x-portable-graymap",
"ppm" => "image/x-portable-pixmap",
"rgb" => "image/x-rgb",
"xbm" => "image/x-xbitmap",
"xpm" => "image/x-xpixmap",
"xwd" => "image/x-xwindowdump",
"igs" => "model/iges",
"msh" => "model/mesh",
"wrl" => "model/vrml",
"css" => "text/css",
"html" => "text/html",
"asc" => "text/plain",
"rtx" => "text/richtext",
"rtf" => "text/rtf",
"sgml" => "text/sgml",
"tsv" => "text/tab-separated-values",
"etx" => "text/x-setext",
"xml" => "text/xml",
"mpeg" => "video/mpeg",
"qt" => "video/quicktime",
"avi" => "video/x-msvideo",
"movie" => "video/x-sgi-movie",
"ice" => "x-conference/x-cooltalk",
"dms" => "application/octet-stream",
"eps" => "application/postscript",
"smil" => "application/smil",
"dir" => "application/x-director",
"skd" => "application/x-koan",
"cdf" => "application/x-netcdf",
"texi" => "application/x-texinfo",
"tr" => "application/x-troff",
"snd" => "audio/basic",
"midi" => "audio/midi",
"mp2" => "audio/mpeg",
"aiff" => "audio/x-aiff",
"rm" => "audio/x-pn-realaudio",
"xyz" => "chemical/x-pdb",
"jpg" => "image/jpeg",
"tif" => "image/tiff",
"iges" => "model/iges",
"mesh" => "model/mesh",
"vrml" => "model/vrml",
"htm" => "text/html",
"txt" => "text/plain",
"sgm" => "text/sgml",
"mpg" => "video/mpeg",
"mov" => "video/quicktime",
"lha" => "application/octet-stream",
"ps" => "application/postscript",
"dxr" => "application/x-director",
"skt" => "application/x-koan",
"roff" => "application/x-troff",
"kar" => "audio/midi",
"mp3" => "audio/mpeg",
"aifc" => "audio/x-aiff",
"jpe" => "image/jpeg",
"silo" => "model/mesh",
"mpe" => "video/mpeg",
"lzh" => "application/octet-stream",
"skm" => "application/x-koan",
"exe" => "application/octet-stream",
"class" => "application/octet-stream"
);
?>
