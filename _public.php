<?php
if (!defined('DC_RC_PATH')) {
  return;
}

$core->addBehavior('publicEntryAfterContent', ['origineConfig', 'publicEntryAfterContent']);
$core->addBehavior('publicEntryBeforeContent', ['origineConfig', 'publicEntryBeforeContent']);
$core->addBehavior('publicHeadContent', ['origineConfig', 'publicHeadContent']);

class origineConfig
{
  /**
   * DOCUMENTATION
   */
  public static function origineConfigArrayToCSS($rules, $rule_type = '')
  {
    $css = '';

    if ($rules) {
      foreach ($rules as $key => $value) {
        if (is_array($value) && !empty($value)) {
          $selector   = $key;
          $properties = $value;

          $css .= $selector . '{';

          foreach ($properties as $property => $rule) {
            $css .= $property . ':' . str_replace(', ', ',', $rule) . ';';
          }

          $css .= '}';
        }
      }
    }

    return $css;
  }

  public static function publicHeadContent()
  {
    global $core;

    if ($core->blog->settings->origineConfig->activation === true) {
      $css = array();

      if ($core->blog->settings->origineConfig->content_font_family !== 'sans-serif') {
        $css['body']['font-family'] = 'Iowan Old Style, Apple Garamond, Baskerville, Times New Roman, Droid Serif, Times, Source Serif Pro, serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol';
      } else {
        $css['body']['font-family'] = '-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Ubuntu, roboto, noto, arial, sans-serif';
      }

      if ($core->blog->settings->origineConfig->content_font_size) {
        $css['body']['font-size'] = abs((int) $core->blog->settings->origineConfig->content_font_size) . 'pt';
      }

      if ($core->blog->settings->origineConfig->content_text_align === 'justify') {
        $css['.content']['text-align'] = 'justify';
      }

      if ($core->blog->settings->origineConfig->content_hyphens === true ) {
        $css['.content']['-webkit-hyphens'] = 'auto';
        $css['.content']['-moz-hyphens']    = 'auto';
        $css['.content']['-ms-hyphens']     = 'auto';
        $css['.content']['hyphens']         = 'auto';

        $css['.content']['-webkit-hyphenate-limit-chars'] = '5 2 2';
        $css['.content']['-moz-hyphenate-limit-chars']    = '5 2 2';
        $css['.content']['-ms-hyphenate-limit-chars']     = '5 2 2';

        $css['.content']['-moz-hyphenate-limit-lines'] = '2';
        $css['.content']['-ms-hyphenate-limit-lines']  = '2';
        $css['.content']['hyphenate-limit-lines']      = '2';

        $css['.content']['-webkit-hyphenate-limit-last'] = 'always';
        $css['.content']['-moz-hyphenate-limit-last']    = 'always';
        $css['.content']['-ms-hyphenate-limit-last']     = 'always';
        $css['.content']['hyphenate-limit-last']         = 'always';

        $css['.content']['-webkit-hyphens'] = 'none';
        $css['.content']['-moz-hyphens']    = 'none';
        $css['.content']['-ms-hyphens']     = 'none';
        $css['.content']['hyphens']         = 'none';
      }


      echo '<style type="text/css">' . self::origineConfigArrayToCSS($css) . '</style>' . "\n";

      // Loads the stylesheet.
      //echo dcUtils::cssLoad($core->blog->getPF('origineConfig/css/style.css'), 'all');
    }
  }
}
