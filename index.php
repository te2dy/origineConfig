<?php
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$core->blog->settings->addNamespace('origineConfig');

if (is_null($core->blog->settings->origineConfig->activation)) {
  try {
    // Activation
    $core->blog->settings->origineConfig->put('activation', false, 'boolean', 'Enable/disable the settings', false);

    // Design
    $core->blog->settings->origineConfig->put('color_scheme', 'system', 'string', 'Color scheme', false);

    // Content Formatting
    $core->blog->settings->origineConfig->put('content_font_family', 'serif', 'string', 'Font family', false);
    $core->blog->settings->origineConfig->put('content_font_size', 12, 'integer', 'Font size', false);
    $core->blog->settings->origineConfig->put('content_text_align', 'left', 'string', 'Text align', false);
    $core->blog->settings->origineConfig->put('content_hyphens', false, 'boolean', 'Hyphenation', false);

    // Head
    $core->blog->settings->origineConfig->put('meta_generator', false, 'boolean', 'Generator', false);
    $core->blog->settings->origineConfig->put('meta_og', false, 'boolean', 'Open Graph Protocole', false);
    $core->blog->settings->origineConfig->put('meta_twitter', false, 'boolean', 'Twitter Cards', false);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

// Activation
$activation = (bool) $core->blog->settings->origineConfig->activation;

// Design
$color_scheme = (string) $core->blog->settings->origineConfig->color_scheme;

// Content formatting
$content_font_family = (string) $core->blog->settings->origineConfig->content_font_family;
$content_font_size   = (int) $core->blog->settings->origineConfig->content_font_size;
$content_text_align  = (string) $core->blog->settings->origineConfig->content_text_align;
$content_hyphens     = (bool) $core->blog->settings->origineConfig->content_hyphens;

// Head
$meta_generator = (bool) $core->blog->settings->origineConfig->meta_generator;
$meta_og        = (bool) $core->blog->settings->origineConfig->meta_og;
$meta_twitter   = (bool) $core->blog->settings->origineConfig->meta_twitter;

if (!empty($_POST)) {
  try {
    // Activation
    $activation = !empty($_POST['activation']);

    // Design
    $color_scheme = trim(html::escapeHTML($_POST['color_scheme']));

    // Content formatting
    $content_font_family = trim(html::escapeHTML($_POST['content_font_family']));
    $content_font_size   = abs((int) $_POST['content_font_size']);
    $content_text_align  = trim(html::escapeHTML($_POST['content_text_align']));
    $content_hyphens     = !empty($_POST['content_hyphens']);

    // Head
    $meta_generator = !empty($_POST['meta_generator']);
    $meta_og        = !empty($_POST['meta_og']);
    $meta_twitter   = !empty($_POST['meta_twitter']);

    // Save
    $core->blog->settings->addNamespace('origineConfig');

    // Activation
    $core->blog->settings->origineConfig->put('activation', $activation);

    // Design
    $core->blog->settings->origineConfig->put('color_scheme', $color_scheme);

    // Content formatting
    $core->blog->settings->origineConfig->put('content_font_family', $content_font_family);
    $core->blog->settings->origineConfig->put('content_font_size', $content_font_size);
    $core->blog->settings->origineConfig->put('content_text_align', $content_text_align);
    $core->blog->settings->origineConfig->put('content_hyphens', $content_hyphens);

    // Head
    $core->blog->settings->origineConfig->put('meta_generator', $meta_generator);
    $core->blog->settings->origineConfig->put('meta_og', $meta_og);
    $core->blog->settings->origineConfig->put('meta_twitter', $meta_twitter);

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
      <div class="fieldset">
        <h3><?php echo __('Activation'); ?></h3>

        <p>
          <?php echo form::checkbox('activation', 1, $activation); ?>

          <label for="activation" class="classic"><?php echo __('Enable extension settings'); ?></label>
        </p>
      </div>

      <div class="fieldset">
        <h3><?php echo __('Design'); ?></h3>

        <p class="field wide">
          <label for="color_scheme" class="classic"><?php echo __('Color scheme'); ?></label>

          <?php
          $combo_color_scheme = array(
            __('Follow system') => 'system',
            __('Light')         => 'light',
            __('Dark')          => 'dark',
          );

          echo form::combo('color_scheme', $combo_color_scheme, $color_scheme);
          ?>
        </p>
      </div>

      <div class="fieldset">
        <h3><?php echo __('Text formatting'); ?></h3>

        <p class="field wide">
          <label for="content_font_family" class="classic"><?php echo __('Font family'); ?></label>

          <?php
          $combo_font_family = array(
            __('Serif (default)') => 'serif',
            __('Sans serif')      => 'sans-serif',
          );

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

      <div class="fieldset">
        <h3><?php echo __('HTML header'); ?></h3>

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

      <p>
        <?php echo $core->formNonce(); ?>

        <input type="submit" value="<?php echo __('Save'); ?>" />
      </p>
    </form>
  </body>
</html>
