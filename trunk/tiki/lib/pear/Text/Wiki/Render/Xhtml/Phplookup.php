<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Phplookup rule end renderer for Xhtml
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Phplookup.php,v 1.1 2005-07-30 08:15:26 toggg Exp $
 * @link       http://pear.php.net/package/Text_Wiki
 */

/**
 * This class renders a link to php functions description in XHTML.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki
 */
class Text_Wiki_Render_Xhtml_Phplookup extends Text_Wiki_Render {

    var $conf = array(
        'target' => '_blank',
        'css' => null
    );


    /**
    *
    * Renders a token into text matching the requested format.
    *
    * @access public
    *
    * @param array $options The "options" portion of the token (second
    * element).
    *
    * @return string The text rendered from the token options.
    *
    */

    function token($options)
    {
        $text = trim($options['text']);
        $css = $this->formatConf(' class="%s"', 'css');

        // start the html
        $output = "<a$css";

        // are we targeting another window?
        $target = $this->getConf('target', '');
        if ($target) {
            // use a "popup" window.  this is XHTML compliant, suggested by
            // Aaron Kalin.  uses the $target as the new window name.
            $target = htmlspecialchars($target);
            $output .= " onclick=\"window.open(this.href, '$target');";
            $output .= " return false;\"";
        }

        // take off the final parens for functions
        if (substr($text, -2) == '()') {
            $q = substr($text, 0, -2);
        } else {
            $q = $text;
        }

        $q = htmlspecialchars($q);
        $text = htmlspecialchars($text);

        // finish and return
        $output .= " href=\"http://php.net/$q\">$text</a>";
        return $output;
    }
}
?>
