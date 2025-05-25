<?php

/**
 * Font Size Plugin: Allow different font sizes
 * 
 * @license    GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @author     hyrorre <hiromu.yamashita@hotmail.com>
 */

if (!defined('DOKU_INC')) define('DOKU_INC', realpath(dirname(__FILE__) . '/../../') . '/');
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(DOKU_PLUGIN . 'syntax.php');

class syntax_plugin_fontsizefixed extends DokuWiki_Syntax_Plugin {
    function getInfo() {
        return array(
            'author' => 'hyrorre',
            'email'  => 'hiromu.yamashita@hotmail.com',
            'date'   => '2025-05-25',
            'name'   => 'FontSizeFixed Plugin',
            'desc'   => 'Allow different font sizes.',
            'url'    => 'https://www.dokuwiki.org/plugin:fontsizefixed',
        );
    }

    function getType() {
        return ('formatting');
    }

    function getSort() {
        return 131;
    }

    function connectTo($mode) {
        $this->Lexer->addEntryPattern('##+(?=.*##+)', $mode, 'plugin_fontsizefixed');
        $this->Lexer->addEntryPattern(',,+(?=.*,,+)', $mode, 'plugin_fontsizefixed');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('##+', 'plugin_fontsizefixed');
        $this->Lexer->addExitPattern(',,+', 'plugin_fontsizefixed');
    }

    function handle($match, $state, $pos, $handler) {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                if ($match[1] == '#')
                    $size = (strlen($match) * 20) + 80 . '%';
                else
                    $size = 120 - (strlen($match) * 20) . '%';
                return array($state, $size);
            case DOKU_LEXER_UNMATCHED:
                return array($state, $match);
            case DOKU_LEXER_EXIT:
                return array($state, '');
        }
        return array();
    }

    function render($mode, $renderer, $data) {
        if ($mode == 'xhtml') {
            list($state, $match) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $renderer->doc .= "<span style='font-size:$match;'>";
                    break;
                case DOKU_LEXER_UNMATCHED:
                    $renderer->doc .= $renderer->_xmlEntities($match);
                    break;
                case DOKU_LEXER_EXIT:
                    $renderer->doc .= '</span>';
                    break;
            }
            return true;
        }
        return false;
    }
}
