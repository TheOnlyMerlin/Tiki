<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Mediawiki: Parses for superscripted text.
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @author     Moritz Venn <ritzmo@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Superscript.php,v 1.3 2006/08/18 13:25:02 ritzmo Exp $
 * @link       http://pear.php.net/package/Text_Wiki
 */

/**
 * Parses for superscripted text.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @author     Moritz Venn <ritzmo@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki
 * @see        Text_Wiki_Parse::Text_Wiki_Parse()
 */
class Text_Wiki_Parse_Superscript extends Text_Wiki_Parse {
    
    /**
    * The regular expression used to parse the source text and find
    * matches conforming to this rule.  Used by the parse() method.
    * 
    * @access public
    * @var string
    * @see parse()
    */
    var $regex =  "/<sup>(.*?)<\/sup>/s";
    
    
    /**
    * Generates a replacement for the matched text.  Token options are:
    * 'type' => ['start'|'end'] The starting or ending point of the
    * emphasized text.  The text itself is left in the source.
    * 
    * @access public
    * @param array &$matches The array of matches from parse().
    * @return A pair of delimited tokens to be used as a placeholder in
    * the source text surrounding the text to be emphasized.
    */
    function process(&$matches)
    {
        $start = $this->wiki->addToken(
            $this->rule, array('type' => 'start')
        );
        
        $end = $this->wiki->addToken(
            $this->rule, array('type' => 'end')
        );

        return $start . $matches[1] . $end;
    }
}
?>
