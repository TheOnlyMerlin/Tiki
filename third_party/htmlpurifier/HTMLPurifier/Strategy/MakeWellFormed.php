<?php

/**
 * Takes tokens makes them well-formed (balance end tags, etc.)
 */
class HTMLPurifier_Strategy_MakeWellFormed extends HTMLPurifier_Strategy
{
    
    /**
     * Array stream of tokens being processed.
     */
    protected $tokens;
    
    /**
     * Current index in $tokens.
     */
    protected $t;
    
    /**
     * Current nesting of elements.
     */
    protected $stack;
    
    /**
     * Injectors active in this stream processing.
     */
    protected $injectors;
    
    /**
     * Current instance of HTMLPurifier_Config.
     */
    protected $config;
    
    /**
     * Current instance of HTMLPurifier_Context.
     */
    protected $context;
    
    public function execute($tokens, $config, $context) {
        
        $definition = $config->getHTMLDefinition();
        
        // local variables
        $generator = new HTMLPurifier_Generator($config, $context);
        $escape_invalid_tags = $config->get('Core', 'EscapeInvalidTags');
        $e = $context->get('ErrorCollector', true);
        $t = false; // token index
        $i = false; // injector index
        $token      = false; // the current token
        $reprocess  = false; // whether or not to reprocess the same token
        $stack = array();
        
        // member variables
        $this->stack   =& $stack;
        $this->t       =& $t;
        $this->tokens  =& $tokens;
        $this->config  = $config;
        $this->context = $context;
        
        // context variables
        $context->register('CurrentNesting', $stack);
        $context->register('InputIndex',     $t);
        $context->register('InputTokens',    $tokens);
        $context->register('CurrentToken',   $token);
        
        // -- begin INJECTOR --
        
        $this->injectors = array();
        
        $injectors = $config->getBatch('AutoFormat');
        $def_injectors = $definition->info_injector;
        $custom_injectors = $injectors['Custom'];
        unset($injectors['Custom']); // special case
        foreach ($injectors as $injector => $b) {
            $injector = "HTMLPurifier_Injector_$injector";
            if (!$b) continue;
            $this->injectors[] = new $injector;
        }
        foreach ($def_injectors as $injector) {
            // assumed to be objects
            $this->injectors[] = $injector;
        }
        foreach ($custom_injectors as $injector) {
            if (is_string($injector)) {
                $injector = "HTMLPurifier_Injector_$injector";
                $injector = new $injector;
            }
            $this->injectors[] = $injector;
        }
        
        // give the injectors references to the definition and context
        // variables for performance reasons
        foreach ($this->injectors as $ix => $injector) {
            $error = $injector->prepare($config, $context);
            if (!$error) continue;
            array_splice($this->injectors, $ix, 1); // rm the injector
            trigger_error("Cannot enable {$injector->name} injector because $error is not allowed", E_USER_WARNING);
        }
        
        // -- end INJECTOR --
        
        // a note on punting:
        //      In order to reduce code duplication, whenever some code needs
        //      to make HTML changes in order to make things "correct", the
        //      new HTML gets sent through the purifier, regardless of its
        //      status. This means that if we add a start token, because it
        //      was totally necessary, we don't have to update nesting; we just
        //      punt ($reprocess = true; continue;) and it does that for us.
        
        // isset is in loop because $tokens size changes during loop exec
        for (
            $t = 0;
            $t == 0 || isset($tokens[$t - 1]);
            // only increment if we don't need to reprocess
            $reprocess ? $reprocess = false : $t++
        ) {
            
            // check for a rewind
            if (is_int($i) && $i >= 0) {
                // possibility: disable rewinding if the current token has a
                // rewind set on it already. This would offer protection from
                // infinite loop, but might hinder some advanced rewinding.
                $rewind_to = $this->injectors[$i]->getRewind();
                if (is_int($rewind_to) && $rewind_to < $t) {
                    if ($rewind_to < 0) $rewind_to = 0;
                    while ($t > $rewind_to) {
                        $t--;
                        $prev = $tokens[$t];
                        // indicate that other injectors should not process this token,
                        // but we need to reprocess it
                        unset($prev->skip[$i]);
                        $prev->rewind = $i;
                        if ($prev instanceof HTMLPurifier_Token_Start) array_pop($this->stack);
                        elseif ($prev instanceof HTMLPurifier_Token_End) $this->stack[] = $prev->start;
                    }
                }
                $i = false;
            }
            
            // handle case of document end
            if (!isset($tokens[$t])) {
                // kill processing if stack is empty
                if (empty($this->stack)) break;
                
                // peek
                $top_nesting = array_pop($this->stack);
                $this->stack[] = $top_nesting;
                
                // send error
                if ($e && !isset($top_nesting->armor['MakeWellFormed_TagClosedError'])) {
                    $e->send(E_NOTICE, 'Strategy_MakeWellFormed: Tag closed by document end', $top_nesting);
                }
                
                // append, don't splice, since this is the end
                $tokens[] = new HTMLPurifier_Token_End($top_nesting->name);
                
                // punt!
                $reprocess = true;
                continue;
            }
            
            // if all goes well, this token will be passed through unharmed
            $token = $tokens[$t];
            
            //echo '<hr>';
            //printTokens($tokens, $t);
            //var_dump($this->stack);
            
            // quick-check: if it's not a tag, no need to process
            if (empty($token->is_tag)) {
                if ($token instanceof HTMLPurifier_Token_Text) {
                    foreach ($this->injectors as $i => $injector) {
                        if (isset($token->skip[$i])) continue;
                        if ($token->rewind !== null && $token->rewind !== $i) continue;
                        $injector->handleText($token);
                        $this->processToken($token, $i);
                        $reprocess = true;
                        break;
                    }
                }
                // another possibility is a comment
                continue;
            }
            
            if (isset($definition->info[$token->name])) {
                $type = $definition->info[$token->name]->child->type;
            } else {
                $type = false; // Type is unknown, treat accordingly
            }
            
            // quick tag checks: anything that's *not* an end tag
            $ok = false;
            if ($type === 'empty' && $token instanceof HTMLPurifier_Token_Start) {
                // claims to be a start tag but is empty
                $token = new HTMLPurifier_Token_Empty($token->name, $token->attr);
                $ok = true;
            } elseif ($type && $type !== 'empty' && $token instanceof HTMLPurifier_Token_Empty) {
                // claims to be empty but really is a start tag
                $this->swap(new HTMLPurifier_Token_End($token->name));
                $this->insertBefore(new HTMLPurifier_Token_Start($token->name, $token->attr));
                // punt (since we had to modify the input stream in a non-trivial way)
                $reprocess = true;
                continue;
            } elseif ($token instanceof HTMLPurifier_Token_Empty) {
                // real empty token
                $ok = true;
            } elseif ($token instanceof HTMLPurifier_Token_Start) {
                // start tag
                
                // ...unless they also have to close their parent
                if (!empty($this->stack)) {
                    
                    $parent = array_pop($this->stack);
                    $this->stack[] = $parent;
                    
                    if (isset($definition->info[$parent->name])) {
                        $elements = $definition->info[$parent->name]->child->getNonAutoCloseElements($config);
                        $autoclose = !isset($elements[$token->name]);
                    } else {
                        $autoclose = false;
                    }
                    
                    if ($autoclose) {
                        if ($e) $e->send(E_NOTICE, 'Strategy_MakeWellFormed: Tag auto closed', $parent);
                        // insert parent end tag before this tag
                        $new_token = new HTMLPurifier_Token_End($parent->name);
                        $new_token->start = $parent;
                        $this->insertBefore($new_token);
                        $reprocess = true;
                        continue;
                    }
                    
                }
                $ok = true;
            }
            
            if ($ok) {
                foreach ($this->injectors as $i => $injector) {
                    if (isset($token->skip[$i])) continue;
                    if ($token->rewind !== null && $token->rewind !== $i) continue;
                    $injector->handleElement($token);
                    $this->processToken($token, $i);
                    $reprocess = true;
                    break;
                }
                if (!$reprocess) {
                    // ah, nothing interesting happened; do normal processing
                    $this->swap($token);
                    if ($token instanceof HTMLPurifier_Token_Start) {
                        $this->stack[] = $token;
                    } elseif ($token instanceof HTMLPurifier_Token_End) {
                        throw new HTMLPurifier_Exception('Improper handling of end tag in start code; possible error in MakeWellFormed');
                    }
                }
                continue;
            }
            
            // sanity check: we should be dealing with a closing tag
            if (!$token instanceof HTMLPurifier_Token_End) {
                throw new HTMLPurifier_Exception('Unaccounted for tag token in input stream, bug in HTML Purifier');
            }
            
            // make sure that we have something open
            if (empty($this->stack)) {
                if ($escape_invalid_tags) {
                    if ($e) $e->send(E_WARNING, 'Strategy_MakeWellFormed: Unnecessary end tag to text');
                    $this->swap(new HTMLPurifier_Token_Text(
                        $generator->generateFromToken($token)
                    ));
                } else {
                    $this->remove();
                    if ($e) $e->send(E_WARNING, 'Strategy_MakeWellFormed: Unnecessary end tag removed');
                }
                $reprocess = true;
                continue;
            }
            
            // first, check for the simplest case: everything closes neatly.
            // Eventually, everything passes through here; if there are problems
            // we modify the input stream accordingly and then punt, so that
            // the tokens get processed again.
            $current_parent = array_pop($this->stack);
            if ($current_parent->name == $token->name) {
                $token->start = $current_parent;
                foreach ($this->injectors as $i => $injector) {
                    if (isset($token->skip[$i])) continue;
                    if ($token->rewind !== null && $token->rewind !== $i) continue;
                    $injector->handleEnd($token);
                    $this->processToken($token, $i);
                    $this->stack[] = $current_parent;
                    $reprocess = true;
                    break;
                }
                continue;
            }
            
            // okay, so we're trying to close the wrong tag
            
            // undo the pop previous pop
            $this->stack[] = $current_parent;
            
            // scroll back the entire nest, trying to find our tag.
            // (feature could be to specify how far you'd like to go)
            $size = count($this->stack);
            // -2 because -1 is the last element, but we already checked that
            $skipped_tags = false;
            for ($j = $size - 2; $j >= 0; $j--) {
                if ($this->stack[$j]->name == $token->name) {
                    $skipped_tags = array_slice($this->stack, $j);
                    break;
                }
            }
            
            // we didn't find the tag, so remove
            if ($skipped_tags === false) {
                if ($escape_invalid_tags) {
                    $this->swap(new HTMLPurifier_Token_Text(
                        $generator->generateFromToken($token)
                    ));
                    if ($e) $e->send(E_WARNING, 'Strategy_MakeWellFormed: Stray end tag to text');
                } else {
                    $this->remove();
                    if ($e) $e->send(E_WARNING, 'Strategy_MakeWellFormed: Stray end tag removed');
                }
                $reprocess = true;
                continue;
            }
            
            // do errors, in REVERSE $j order: a,b,c with </a></b></c>
            $c = count($skipped_tags);
            if ($e) {
                for ($j = $c - 1; $j > 0; $j--) {
                    // notice we exclude $j == 0, i.e. the current ending tag, from
                    // the errors...
                    if (!isset($skipped_tags[$j]->armor['MakeWellFormed_TagClosedError'])) {
                        $e->send(E_NOTICE, 'Strategy_MakeWellFormed: Tag closed by element end', $skipped_tags[$j]);
                    }
                }
            }
            
            // insert tags, in FORWARD $j order: c,b,a with </a></b></c>
            for ($j = 1; $j < $c; $j++) {
                // ...as well as from the insertions
                $new_token = new HTMLPurifier_Token_End($skipped_tags[$j]->name);
                $new_token->start = $skipped_tags[$j];
                $this->insertBefore($new_token);
            }
            $reprocess = true;
            continue;
        }
        
        $context->destroy('CurrentNesting');
        $context->destroy('InputTokens');
        $context->destroy('InputIndex');
        $context->destroy('CurrentToken');
        
        unset($this->injectors, $this->stack, $this->tokens, $this->t);
        return $tokens;
    }
    
    /**
     * Processes arbitrary token values for complicated substitution patterns.
     * In general:
     * 
     * If $token is an array, it is a list of tokens to substitute for the
     * current token. These tokens then get individually processed. If there
     * is a leading integer in the list, that integer determines how many
     * tokens from the stream should be removed.
     * 
     * If $token is a regular token, it is swapped with the current token.
     * 
     * If $token is false, the current token is deleted.
     * 
     * If $token is an integer, that number of tokens (with the first token
     * being the current one) will be deleted.
     * 
     * @param $token Token substitution value
     * @param $injector Injector that performed the substitution; default is if
     *        this is not an injector related operation.
     */
    protected function processToken($token, $injector = -1) {
        
        // normalize forms of token
        if (is_object($token)) $token = array(1, $token);
        if (is_int($token))    $token = array($token);
        if ($token === false)  $token = array(1);
        if (!is_array($token)) throw new HTMLPurifier_Exception('Invalid token type from injector');
        if (!is_int($token[0])) array_unshift($token, 1);
        if ($token[0] === 0) throw new HTMLPurifier_Exception('Deleting zero tokens is not valid');
        
        // $token is now an array with the following form:
        // array(number nodes to delete, new node 1, new node 2, ...)
        
        $delete = array_shift($token);
        $old = array_splice($this->tokens, $this->t, $delete, $token);
        
        if ($injector > -1) {
            // determine appropriate skips
            $oldskip = isset($old[0]) ? $old[0]->skip : array();
            foreach ($token as $object) {
                $object->skip = $oldskip;
                $object->skip[$injector] = true;
            }
        }
        
    }
    
    /**
     * Inserts a token before the current token. Cursor now points to this token
     */
    private function insertBefore($token) {
        array_splice($this->tokens, $this->t, 0, array($token));
    }

    /**
     * Removes current token. Cursor now points to new token occupying previously
     * occupied space.
     */
    private function remove() {
        array_splice($this->tokens, $this->t, 1);
    }
    
    /**
     * Swap current token with new token. Cursor points to new token (no change).
     */
    private function swap($token) {
        $this->tokens[$this->t] = $token;
    }
    
}

