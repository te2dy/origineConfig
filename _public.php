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

    if ($core->blog->settings->origineConfig->activation == true) {
      $css = array();

      /**
       * Styles
       */
      if ($core->blog->settings->origineConfig->content_font_family !== 'sans-serif') {
        $css['body']['font-family'] = '"Iowan Old Style", "Apple Garamond", Baskerville, "Times New Roman", "Droid Serif", Times, "Source Serif Pro", serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';
      } else {
        $css['body']['font-family'] = '-apple-system, BlinkMacSystemFont, "Avenir Next", Avenir, "Segoe UI", "Helvetica Neue", Helvetica, Ubuntu, Roboto, Noto, Arial, sans-serif';
      }

      if ($core->blog->settings->origineConfig->content_font_size) {
        $css['body']['font-size'] = abs((int) $core->blog->settings->origineConfig->content_font_size) . 'pt';
      }

      if ($core->blog->settings->origineConfig->content_text_align === 'justify') {
        $css['.content']['text-align'] = 'justify';
      }

      if ($core->blog->settings->origineConfig->content_hyphens == true ) {
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

      /**
       * Meta
       */

       // Generator
       if ($core->blog->settings->origineConfig->meta_generator == true) {
         echo '<meta name="generator" content="Dotclear" />' . "\n";
       }

       // Open Graph
       if ($core->blog->settings->origineConfig->meta_og == true) {
         global $_ctx;

         $type = $core->url->type;

         //var_dump($core->blog);

         $open_graph = array();

         switch ($type) {
           // Home
           case 'default' :
           case 'static' :
             $open_graph['og:title']       = html::escapeHTML($core->blog->name);
             $open_graph['og:description'] = html::escapeHTML($core->blog->desc);
             $open_graph['og:url']         = html::escapeURL($core->blog->url);
             $open_graph['og:image']       = context::EntryFirstImageHelper('o', true, '', true);

             if ($open_graph['og:image']) {
               $image_tag = context::EntryFirstImageHelper('o', true, '', false);
               if (preg_match('/alt="([^"]+)"/', $image_tag, $alt)) {
                 $open_graph['og:image:alt'] = $alt[1];
               }
             }
             break;

           case 'post' :
             $open_graph['og:title'] = html::escapeHTML($_ctx->posts->post_title);

             if ($_ctx->posts->getExcerpt()) {
               $desc_content = $_ctx->posts->getExcerpt();
               $desc_content = html::decodeEntities(html::clean($desc_content));
               $desc_content = preg_replace('/\s+/', ' ', $desc_content);
               $desc_content = html::escapeHTML($desc_content);
             } else {
               $desc_content = $_ctx->posts->getContent();
               $desc_content = html::decodeEntities(html::clean($desc_content));
               $desc_content = preg_replace('/\s+/', ' ', $desc_content);
               $desc_content = html::escapeHTML($desc_content);

               if (strlen($desc_content) >= 249) {
                 $desc_content = text::cutString($desc_content, 249);

                 if ( substr($desc_content, -1) !== '.' ) {
                   $desc_content = text::cutString($desc_content, 249) . '…';
                 } else {
                   $desc_content  = substr($desc_content, 0, -1);
                   $desc_content .= '…';
                 }
               }
             }

             $open_graph['og:description'] = $desc_content;
             $open_graph['og:image']       = context::EntryFirstImageHelper('o', true, '', true);

             if ($open_graph['og:image']) {
               $image_tag = context::EntryFirstImageHelper('o', true, '', false);
               if (preg_match('/alt="([^"]+)"/', $image_tag, $alt)) {
                 $open_graph['og:image:alt'] = $alt[1];
               }
             }
             break;
         }

         if (!empty($open_graph)) {
           echo '<!-- OpenGraph -->' . "\n";

           foreach ($open_graph as $property => $content) {
             if ($content) {
               echo '<meta property="' . $property . '" content="' . $content . '" />' . "\n";
             }
           }
         }
       }

    }
  }
}
