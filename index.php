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

          <label for="activation" class="classic"><?php echo __('Activer les réglages de l’extension'); ?></label>
        </p>
      </div>

      <div class="fieldset">
        <h3><?php echo __('Apparence'); ?></h3>

        <p class="field wide">
          <label for="color_scheme" class="classic"><?php echo __('Schéma de couleurs'); ?></label>

          <?php
          $combo_color_scheme = array(
            __('Suivre le système (par défaut)') => 'system',
            __('Clair')                          => 'light',
            __('Sombre')                         => 'dark',
          );

          echo form::combo('color_scheme', $combo_color_scheme, $color_scheme);
          ?>
        </p>
      </div>

      <div class="fieldset">
        <h3><?php echo __('Mise en forme du texte'); ?></h3>

        <p class="field wide">
          <label for="content_font_family" class="classic"><?php echo __('Famille de police d’écriture'); ?></label>

          <?php
          $combo_font_family = array(
            __('Avec empattements (par défaut)') => 'serif',
            __('Sans empattements')              => 'sans-serif',
          );

          echo form::combo('content_font_family', $combo_font_family, $content_font_family);
          ?>
        </p>

        <p class="form-note">
          <?php echo __('Dans tous les cas, votre thème chargera les polices du système de l’appareil à partir duquel votre site est consulté. Cela permet de réduire les temps de chargements ainsi qu’une continuité graphique avec le système.'); ?>
        </p>

        <p class="field wide">
          <label for="content_font_size" class="classic">
            <?php echo __('Taille du texte'); ?>
          </label>

          <?php
          $combo_font_size = [
              __('11 pt')              => 11,
              __('12 pt (par défaut)') => 12,
              __('13 pt')              => 13,
          ];

          echo form::combo('content_font_size', $combo_font_size, $content_font_size);
          ?>
        </p>

        <p class="field wide">
          <label for="content_text_align" class="classic">
            <?php echo __('Alignement du texte'); ?>
          </label>

          <?php
          $combo_text_align = [
              __('Gauche (par défaut)') => 'left',
              __('Justifié')            => 'justify',
          ];

          echo form::combo('content_text_align', $combo_text_align, $content_text_align);
          ?>
        </p>

        <p class="field wide">
          <label for="content_hyphens" class="classic">
            <?php echo __('Activer la césure automatique'); ?>
          </label>

          <?php echo form::checkbox('content_hyphens', 1, $content_hyphens); ?>
        </p>

        <p class="form-note">
          <?php echo __('Désactivées par défaut, la césure automatique est recommandées lorsque l’alignement du texte est réglé sur « justifié ».'); ?>
        </p>
      </div>

      <div class="fieldset">
        <h3><?php echo __('En-tête HTML des pages'); ?></h3>

        <p class="form-note">
          <?php echo __('Permet d’ajouter des informations dans vos pages sans les afficher à l’écran de vos lecteurs.'); ?>
        </p>

        <p class="field wide">
          <label for="meta_generator" class="classic">
            <?php echo __('Ajouter la balise meta <code>generator</code>'); ?>
          </label>

          <?php echo form::checkbox('meta_generator', 1, $meta_generator); ?>
        </p>

        <p class="field wide">
          <label for="meta_og" class="classic">
            <?php echo __('Ajouter des balises Open Graph'); ?>
          </label>

          <?php echo form::checkbox('meta_og', 1, $meta_og); ?>
        </p>

        <p class="field wide">
          <label for="meta_twitter" class="classic">
            <?php echo __('Ajouter des balises pour les cartes Twitter'); ?>
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
