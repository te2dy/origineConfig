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

      // Social tags
      if ($core->blog->settings->origineConfig->meta_og === true
        || $core->blog->settings->origineConfig->meta_twitter === true
      ) {
        global $_ctx;

        $social_tags = array(
          'title'       => '',
          'description' => '',
          'url'         => '',
          'image'       => '',
          'image_alt'   => '',
          'image_width' => '',
          'site_name'   => html::escapeHTML($core->blog->name),
        );

        switch ($core->url->type) {

          // Home
          case 'default' :
          case 'static' :
            $social_tags['title']       = $social_tags['site_name'];
            $social_tags['description'] = html::escapeHTML($core->blog->desc);
            $social_tags['url']         = html::escapeURL($core->blog->url);
            $social_tags['image']       = context::EntryFirstImageHelper('o', true, '', true);

            if ($social_tags['image']) {
              $image_tag = context::EntryFirstImageHelper('o', true, '', false);
              if (preg_match('/alt="([^"]+)"/', $image_tag, $alt)) {
                $social_tags['image_alt'] = $alt[1];
              }
            }

            break;

            case 'post' :
            case 'page' :
              $social_tags['title'] = html::escapeHTML($_ctx->posts->post_title);

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

              $social_tags['description'] = $desc_content;
              $social_tags['image']       = context::EntryFirstImageHelper('o', true, '', true);

              if ($social_tags['image']) {
                $image_tag = context::EntryFirstImageHelper('o', true, '', false);
                if (preg_match('/alt="([^"]+)"/', $image_tag, $alt)) {
                  $social_tags['image_alt'] = $alt[1];
                }
              }

              break;
        }

        if (!empty($social_tags)) {

          // OpenGraph
          if ($core->blog->settings->origineConfig->meta_og === true) {

            // Title
            echo $social_tags['title'] ? '<meta property="og:title" content="' . html::escapeHTML($social_tags['title']) . '" />' . "\n" : '';

            // Description
            echo $social_tags['description'] ? '<meta property="og:description" content="' . html::escapeHTML($social_tags['description']) . '" />' . "\n" : '';

            // URL
            echo $social_tags['url'] ? '<meta property="og:url" content="' . html::escapeURL($social_tags['url']) . '" />' . "\n" : '';

            // Site name
            echo $social_tags['site_name'] ? '<meta property="og:site_name" content="' . html::escapeHTML($social_tags['site_name']) . '" />' . "\n" : '';

            // Image
            echo $social_tags['image'] ? '<meta property="og:image" content="' . html::escapeURL($social_tags['image']) . '" />' . "\n" : '';
            echo $social_tags['image_alt'] ? '<meta property="og:image:alt" content="' . html::escapeHTML($social_tags['image_alt']) . '" />' . "\n" : '';
          }

          // Twitter
          if ($core->blog->settings->origineConfig->meta_twitter === true) {
            echo '<meta property="twitter:card" content="summary" />' . "\n";

            // Title
            echo $social_tags['title'] ? '<meta property="twitter:title" content="' . html::escapeHTML($social_tags['title']) . '" />' . "\n" : '';

            // Description
            echo $social_tags['description'] ? '<meta property="twitter:description" content="' . html::escapeHTML($social_tags['description']) . '" />' . "\n" : '';

            // URL
            echo $social_tags['url'] ? '<meta property="twitter:url" content="' . html::escapeURL($social_tags['url']) . '" />' . "\n" : '';

            // Image
            echo $social_tags['image'] ? '<meta property="twitter:image" content="' . html::escapeURL($social_tags['image']) . '" />' . "\n" : '';
            echo $social_tags['image_alt'] ? '<meta property="twitter:image:alt" content="' . html::escapeHTML($social_tags['image_alt']) . '" />' . "\n" : '';
          }
        }
      }

      /**
       * Meta
       */

      // Generator
      if ($core->blog->settings->origineConfig->meta_generator === true) {
        echo '<meta name="generator" content="Dotclear" />' . "\n";
      }
    }
  }
}
