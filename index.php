<?php
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

/**
 * Converts an array to CSS without spaces and line breaks.
 */
function origineConfigArrayToCSS($rules)
{
  $css = '';

  if ($rules) {
    foreach ($rules as $key => $value) {
      if (is_array($value) && !empty($value)) {
        $selector   = $key;
        $properties = $value;

        $css .= str_replace(', ', ',', $selector) . '{';

        if (is_array($properties) && !empty($properties)) {
          foreach ($properties as $property => $rule) {
            $css .= $property . ':' . str_replace(', ', ',', $rule) . ';';
          }
        }

        $css .= '}';
      }
    }
  }

  return $css;
}

$core->blog->settings->addNamespace('origineConfig');

if (is_null($core->blog->settings->origineConfig->activation)) {
  try {
    // Activation
    $core->blog->settings->origineConfig->put('activation', false, 'boolean', 'Enable/disable origineConfig settings', false);

    // Appearance
    $core->blog->settings->origineConfig->put('color_scheme', 'system', 'string', 'Color scheme', false);
    $core->blog->settings->origineConfig->put('content_link_color', 'red', 'string', 'Link color', false);
    $core->blog->settings->origineConfig->put('css_transition', false, 'boolean', 'Color transition on link hover', false);
    $core->blog->settings->origineConfig->put('tb_align', 'left', 'string', 'Header and footer alignment', false);

    // Content Formatting
    $core->blog->settings->origineConfig->put('content_font_family', 'serif', 'string', 'Font family', false);
    $core->blog->settings->origineConfig->put('content_font_size', 12, 'integer', 'Font size', false);
    $core->blog->settings->origineConfig->put('content_text_align', 'left', 'string', 'Text align', false);
    $core->blog->settings->origineConfig->put('content_hyphens', false, 'boolean', 'Hyphenation', false);

    // Head
    $core->blog->settings->origineConfig->put('meta_generator', false, 'boolean', 'Generator', false);
    $core->blog->settings->origineConfig->put('meta_og', false, 'boolean', 'Open Graph Protocole', false);
    $core->blog->settings->origineConfig->put('meta_twitter', false, 'boolean', 'Twitter Cards', false);

    // Post settings
    $core->blog->settings->origineConfig->put('post_author_name', false, 'boolean', 'Author name on posts', false);
    $core->blog->settings->origineConfig->put('post_list_author_name', false, 'boolean', 'Author name on posts in the post list', false);
    $core->blog->settings->origineConfig->put('comment_links', true, 'boolean', 'Link to the comment feed and trackbacks', false);

    // All styles
    $core->blog->settings->origineConfig->put('origine_styles', '', 'string', 'Origine styles', false);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

// Activation
$activation = (bool) $core->blog->settings->origineConfig->activation;

// Appearance
$color_scheme       = (string) $core->blog->settings->origineConfig->color_scheme;
$content_link_color = (string) $core->blog->settings->origineConfig->content_link_color;
$css_transition     = (bool) $core->blog->settings->origineConfig->css_transition;
$css_transition     = (bool) $core->blog->settings->origineConfig->css_transition;
$tb_align           = (string) $core->blog->settings->origineConfig->tb_align;

// Content formatting
$content_font_family = (string) $core->blog->settings->origineConfig->content_font_family;
$content_font_size   = (int) $core->blog->settings->origineConfig->content_font_size;
$content_text_align  = (string) $core->blog->settings->origineConfig->content_text_align;
$content_hyphens     = (bool) $core->blog->settings->origineConfig->content_hyphens;

// Head
$meta_generator = (bool) $core->blog->settings->origineConfig->meta_generator;
$meta_og        = (bool) $core->blog->settings->origineConfig->meta_og;
$meta_twitter   = (bool) $core->blog->settings->origineConfig->meta_twitter;

// Post settings
$post_author_name      = (bool) $core->blog->settings->origineConfig->post_author_name;
$post_list_author_name = (bool) $core->blog->settings->origineConfig->post_list_author_name;
$comment_links         = (bool) $core->blog->settings->origineConfig->comment_links;

// All styles
$origine_styles = (bool) $core->blog->settings->origineConfig->origine_styles;

if (!empty($_POST)) {
  try {

    /**
     * Get settings from the form
     * and escape them.
     */

    // Activation
    $activation = !empty($_POST['activation']);

    // Appearance
    $color_scheme       = trim(html::escapeHTML($_POST['color_scheme']));
    $content_link_color = trim(html::escapeHTML($_POST['content_link_color']));
    $css_transition     = !empty($_POST['css_transition']);
    $tb_align           = trim(html::escapeHTML($_POST['tb_align']));

    // Content formatting
    $content_font_family = trim(html::escapeHTML($_POST['content_font_family']));
    $content_font_size   = abs((int) $_POST['content_font_size']);
    $content_text_align  = trim(html::escapeHTML($_POST['content_text_align']));
    $content_hyphens     = !empty($_POST['content_hyphens']);

    // Head
    $meta_generator = !empty($_POST['meta_generator']);
    $meta_og        = !empty($_POST['meta_og']);
    $meta_twitter   = !empty($_POST['meta_twitter']);

    // Post settings
    $post_author_name      = !empty($_POST['post_author_name']);
    $post_list_author_name = !empty($_POST['post_list_author_name']);
    $comment_links         = !empty($_POST['comment_links']);

    // All Styles
    $origine_styles = trim(html::escapeHTML($_POST['origine_styles']));

    /**
     * Save settings in the database.
     */
    $core->blog->settings->addNamespace('origineConfig');

    // Activation
    $core->blog->settings->origineConfig->put('activation', $activation);

    // Appearance
    $core->blog->settings->origineConfig->put('color_scheme', $color_scheme);
    $core->blog->settings->origineConfig->put('content_link_color', $content_link_color);
    $core->blog->settings->origineConfig->put('css_transition', $css_transition);
    $core->blog->settings->origineConfig->put('tb_align', $tb_align);

    // Content formatting
    $core->blog->settings->origineConfig->put('content_font_family', $content_font_family);
    $core->blog->settings->origineConfig->put('content_font_size', $content_font_size);
    $core->blog->settings->origineConfig->put('content_text_align', $content_text_align);
    $core->blog->settings->origineConfig->put('content_hyphens', $content_hyphens);

    // Head section
    $core->blog->settings->origineConfig->put('meta_generator', $meta_generator);
    $core->blog->settings->origineConfig->put('meta_og', $meta_og);
    $core->blog->settings->origineConfig->put('meta_twitter', $meta_twitter);

    // Post settings
    $core->blog->settings->origineConfig->put('post_author_name', $post_author_name);
    $core->blog->settings->origineConfig->put('post_list_author_name', $post_list_author_name);
    $core->blog->settings->origineConfig->put('comment_links', $comment_links);

    /**
     * And save styles too!
     */
    $link_colors = [
      'red'    => [
        'light' => '#de0000',
        'dark'  => '#f14646',
      ],
      'blue'   => [
        'light' => '#0057B7',
        'dark'  => '#529ff5',
      ],
      'green'  => [
        'light' => '#006400',
        'dark'  => '#18af18',
      ],
      'orange' => [
        'light' => '#ff8c00',
        'dark'  => '#ffab2e',
      ],
      'purple' => [
        'light' => '#800080',
        'dark'  => '#9a389a',
      ],
    ];

    $the_color = array_key_exists($content_link_color, $link_colors) ? $content_link_color : 'red';

    $css            = [];
    $css_root_array = [];
    $css_root       = '';

    if ($color_scheme === 'system') {
      $css_root_array[':root']['--color-background']             = '#fff';
      $css_root_array[':root']['--color-text-primary']           = '#000';
      $css_root_array[':root']['--color-text-secondary']         = '#595959';
      $css_root_array[':root']['--color-link']                   = $link_colors[$the_color]['light'];
      $css_root_array[':root']['--color-border']                 = '#aaa';
      $css_root_array[':root']['--color-input-text']             = '#000';
      $css_root_array[':root']['--color-input-text-hover']       = '#fff';
      $css_root_array[':root']['--color-input-background']       = '#eaeaea';
      $css_root_array[':root']['--color-input-background-hover'] = '#000';

      $css_root .= origineConfigArrayToCSS($css_root_array);

      // Resets $css_root_array to set the colors for the dark scheme.
      $css_root_array = [];

      $css_root_array[':root']['--color-background']             = '#16161D';
      $css_root_array[':root']['--color-text-primary']           = '#d9d9d9';
      $css_root_array[':root']['--color-text-secondary']         = '#8c8c8c';
      $css_root_array[':root']['--color-link']                   = $link_colors[$the_color]['dark'];
      $css_root_array[':root']['--color-border']                 = '#aaa';
      $css_root_array[':root']['--color-input-text']             = '#d9d9d9';
      $css_root_array[':root']['--color-input-text-hover']       = '#fff';
      $css_root_array[':root']['--color-input-background']       = '#333333';
      $css_root_array[':root']['--color-input-background-hover'] = '#262626';

      $css_root .= '@media (prefers-color-scheme:dark) {' . origineConfigArrayToCSS($css_root_array) . '}';
    } elseif ($color_scheme === 'dark') {
      $css_root_array[':root']['--color-background']             = '#16161D';
      $css_root_array[':root']['--color-text-primary']           = '#d9d9d9';
      $css_root_array[':root']['--color-text-secondary']         = '#8c8c8c';
      $css_root_array[':root']['--color-link']                   = $link_colors[$the_color]['dark'];
      $css_root_array[':root']['--color-border']                 = '#aaa';
      $css_root_array[':root']['--color-input-text']             = '#d9d9d9';
      $css_root_array[':root']['--color-input-text-hover']       = '#fff';
      $css_root_array[':root']['--color-input-background']       = '#333333';
      $css_root_array[':root']['--color-input-background-hover'] = '#262626';

      $css_root .= origineConfigArrayToCSS($css_root_array);
    } else {
      $css_root_array[':root']['--color-background']             = '#fff';
      $css_root_array[':root']['--color-text-primary']           = '#000';
      $css_root_array[':root']['--color-text-secondary']         = '#595959';
      $css_root_array[':root']['--color-link']                   = $link_colors[$the_color]['light'];
      $css_root_array[':root']['--color-border']                 = '#aaa';
      $css_root_array[':root']['--color-input-text']             = '#000';
      $css_root_array[':root']['--color-input-text-hover']       = '#fff';
      $css_root_array[':root']['--color-input-background']       = '#eaeaea';
      $css_root_array[':root']['--color-input-background-hover'] = '#000';

      $css_root .= origineConfigArrayToCSS($css_root_array);
    }

    // Transitions
    if ($css_transition === true) {
      $css['a']['transition'] = 'all .2s ease-in-out';
      $css['a:active, a:focus, a:hover']['transition'] = 'all .2s ease-in-out';
    }

    // Header and footer alignment
    if ($tb_align === 'left') {
      $css['#site-header']['text-align'] = 'left';
      $css['#site-footer']['text-align'] = 'left';
    } else {
      $css['#site-header']['text-align'] = 'center';
      $css['#site-footer']['text-align'] = 'center';
    }

    // Font family
    if ($content_font_family !== ('sans-serif' || 'mono') ) {
      $css['body']['font-family'] = '"Iowan Old Style", "Apple Garamond", Baskerville, "Times New Roman", "Droid Serif", Times, "Source Serif Pro", serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';
    } elseif ($content_font_family === 'sans-serif') {
      $css['body']['font-family'] = '-apple-system, BlinkMacSystemFont, "Avenir Next", Avenir, "Segoe UI", "Helvetica Neue", Helvetica, Ubuntu, Roboto, Noto, Arial, sans-serif';
    } else {
      $css['body']['font-family'] = 'Menlo, Consolas, Monaco, "Liberation Mono", "Lucida Console", monospace';
    }

    // Font size
    if ($content_font_size) {
      $css['body']['font-size'] = abs((int) $content_font_size) . 'pt';
    }

    // Text align
    if ($content_text_align === 'justify') {
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['text-align'] = 'justify';
    }

    // Hyphens
    if ($content_hyphens === true ) {
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-webkit-hyphens'] = 'auto';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-moz-hyphens']    = 'auto';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-ms-hyphens']     = 'auto';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['hyphens']         = 'auto';

      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-webkit-hyphenate-limit-chars'] = '5 2 2';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-moz-hyphenate-limit-chars']    = '5 2 2';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-ms-hyphenate-limit-chars']     = '5 2 2';

      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-moz-hyphenate-limit-lines'] = '2';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-ms-hyphenate-limit-lines']  = '2';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['hyphenate-limit-lines']      = '2';

      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-webkit-hyphenate-limit-last'] = 'always';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-moz-hyphenate-limit-last']    = 'always';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-ms-hyphenate-limit-last']     = 'always';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['hyphenate-limit-last']         = 'always';
    } else {
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-webkit-hyphens'] = 'none';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-moz-hyphens']    = 'none';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['-ms-hyphens']     = 'none';
      $css['.content p, .content ol li, .content ul li, .post-excerpt']['hyphens']         = 'none';
    }

    $css_last = '';

    // Redured motion
    if ( $css_transition === true ) {
      $css_redured_motion                                             = [];
      $css_redured_motion['a']['transition']                          = 'none';
      $css_redured_motion['a:active, a:focus, a:hover']['transition'] = 'none';

      $css_last = '@media(prefers-reduced-motion:reduce) {' . origineConfigArrayToCSS($css_redured_motion) . '}';
    }

    $core->blog->settings->origineConfig->put('origine_styles', htmlspecialchars($css_root . origineConfigArrayToCSS($css), ENT_NOQUOTES) . $css_last);

    $core->blog->triggerBlog();

    dcPage::addSuccessNotice(__('Settings have been successfully updated.'));
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}
?>
<html>
  <head>
  	<title><?php echo(__('Origine Settings')); ?></title>
  </head>
  <body>
    <?php
    echo dcPage::breadcrumb(
      array(
        html::escapeHTML($core->blog->name) => '',
        __('Origine Settings')              => '',
      )
    );

    echo dcPage::notices();
    ?>

    <form action="<?php echo $p_url; ?>" method="post">
      <p>
        <?php echo form::checkbox('activation', 1, $activation); ?>

        <label for="activation" class="classic"><?php echo __('Enable extension settings'); ?></label>
      </p>

      <h3><?php echo __('Design'); ?></h3>

      <div class="fieldset">
        <h4><?php echo __('Colors'); ?></h4>

        <p class="field wide">
          <label for="color_scheme" class="classic"><?php echo __('Color scheme'); ?></label>

          <?php
          $combo_color_scheme = [
            __('Follow system') => 'system',
            __('Light')         => 'light',
            __('Dark')          => 'dark',
          ];

          echo form::combo('color_scheme', $combo_color_scheme, $color_scheme);
          ?>
        </p>

        <p class="field wide">
          <label for="content_link_color" class="classic">
            <?php echo __('Link color'); ?>
          </label>

          <?php
          $combo_link_color = [
              __('Red (default)') => 'red',
              __('Blue')          => 'blue',
              __('Green')         => 'green',
              __('Orange')        => 'orange',
              __('Purple')        => 'purple',
          ];

          echo form::combo('content_link_color', $combo_link_color, $content_link_color);
          ?>
        </p>

        <!-- A METTRE EN PLACE. -->
        <p class="field wide">
          <label for="css_transition" class="classic">
            <?php echo __('Fade effect when hovering links'); ?>
          </label>

          <?php echo form::checkbox('css_transition', 1, $css_transition); ?>
        </p>

        <h4><?php echo __('Layout'); ?></h4>

        <p class="field wide">
          <label for="" class="classic">
            <?php echo __('Header and footer alignment'); ?>
          </label>

          <?php
          $combo_tb_align = [
              __('Left')   => 'left',
              __('Center') => 'center',
          ];

          echo form::combo('tb_align', $combo_tb_align, $tb_align);
          ?>
        </p>

        <h4><?php echo __('Text formatting'); ?></h4>

        <p class="field wide">
          <label for="content_font_family" class="classic"><?php echo __('Font family'); ?></label>

          <?php
          $combo_font_family = [
            __('Serif (default)') => 'serif',
            __('Sans serif')      => 'sans-serif',
            __('Monospace')       => 'monospace',
          ];

          echo form::combo('content_font_family', $combo_font_family, $content_font_family);
          ?>
        </p>

        <p class="form-note">
          <?php echo __('In any case, your theme will load system fonts of the device from which your site is viewed. This allows to reduce loading times and to have a graphic continuity with the system.'); ?>
        </p>

        <p class="field wide">
          <label for="content_font_size" class="classic">
            <?php echo __('Font size'); ?>
          </label>

          <?php
          $combo_font_size = [
              __('11pt')              => 11,
              __('12pt (default)') => 12,
              __('13pt')              => 13,
          ];

          echo form::combo('content_font_size', $combo_font_size, $content_font_size);
          ?>
        </p>

        <p class="field wide">
          <label for="content_text_align" class="classic">
            <?php echo __('Text align'); ?>
          </label>

          <?php
          $combo_text_align = [
              __('Left (default)') => 'left',
              __('Justified')      => 'justify',
          ];

          echo form::combo('content_text_align', $combo_text_align, $content_text_align);
          ?>
        </p>

        <p class="field wide">
          <label for="content_hyphens" class="classic">
            <?php echo __('Enable automatic hyphenation'); ?>
          </label>

          <?php echo form::checkbox('content_hyphens', 1, $content_hyphens); ?>
        </p>

        <p class="form-note">
          <?php echo __('Disabled by default, automatic hyphenation is recommended when text alignment is set to "justified".'); ?>
        </p>
      </div>

      <h3><?php echo __('Advanced settings'); ?></h3>

      <div class="fieldset">
        <p class="form-note">
          <?php echo __("Allows you to add information to your pages without displaying it on your readers' screen."); ?>
        </p>

        <p class="field wide">
          <label for="meta_generator" class="classic">
            <?php echo __('Add meta tag <code>generator</code>'); ?>
          </label>

          <?php echo form::checkbox('meta_generator', 1, $meta_generator); ?>
        </p>

        <p class="field wide">
          <label for="meta_og" class="classic">
            <?php echo __('Add Open Graph tags'); ?>
          </label>

          <?php echo form::checkbox('meta_og', 1, $meta_og); ?>
        </p>

        <p class="field wide">
          <label for="meta_twitter" class="classic">
            <?php echo __('Add Twitter Cards tags'); ?>
          </label>

          <?php echo form::checkbox('meta_twitter', 1, $meta_twitter); ?>
        </p>
      </div>

      <!-- A METTRE EN PLACE. -->
      <h3><?php echo __('Post settings'); ?></h3>

      <div class="fieldset">
        <h4>Author</h4>

        <p class="field wide">
          <label for="" class="classic">
            <?php echo __('Display the author name on full posts'); ?>
          </label>

          <?php echo form::checkbox('post_author_name', 1, $post_author_name); ?>
        </p>

        <p class="field wide">
          <label for="" class="classic">
            <?php echo __('Display the author name on posts in the post list'); ?>
          </label>

          <?php echo form::checkbox('post_list_author_name', 1, $post_list_author_name); ?>
        </p>

        <h4>Comments</h4>

        <p class="field wide">
          <label for="comment_links" class="classic">
            <?php echo __('Add a link to the comment feed and trackbacks below the comment section'); ?>
          </label>

          <?php echo form::checkbox('comment_links', 1, $comment_links); ?>
        </p>
      </div>

      <p>
        <?php echo $core->formNonce(); ?>

        <input type="submit" value="<?php echo __('Save'); ?>" />
      </p>

      <p class="form-note warn">
        <?php
        if ($core->plugins->moduleExists('maintenance') === true) {
          printf(
            __('If the changes are not effective after saving, consider emptying the templates cache directory with the <a href="%s">Maintenance</a> plugin (Servicing › Purge).'),
            html::escapeURL($core->adminurl->get('admin.plugin', ['p' => 'maintenance']))
          );
        } else {
          printf(
            __('If the changes are not effective after saving, consider emptying the templates cache directory using the Maintenance plugin, which you can activate from the <a href="%s">Plugins management</a> page.'),
            html::escapeURL($core->adminurl->get('admin.plugins') . '#plugin-deactivate')
          );
        }
        ?>
      </p>
    </form>
  </body>
</html>
