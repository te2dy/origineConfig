<?php
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$core->blog->settings->addNamespace('origineConfig');

if (is_null($core->blog->settings->origineConfig->activation)) {
  try {
    $core->blog->settings->origineConfig->put('activation', false, 'boolean', 'Enable/disable the settings', false);
    $core->blog->settings->origineConfig->put('content_font_family', 'serif', 'string', 'Font family', false);
    $core->blog->settings->origineConfig->put('content_font_size', 12, 'integer', 'Font size', false);
    $core->blog->settings->origineConfig->put('content_text_align', 'left', 'string', 'Text align', false);
    $core->blog->settings->origineConfig->put('content_hyphens', false, 'boolean', 'Hyphenation', false);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

$activation          = (bool) $core->blog->settings->origineConfig->activation;
$content_font_family = (string) $core->blog->settings->origineConfig->content_font_family;
$content_font_size   = (int) $core->blog->settings->origineConfig->content_font_size;
$content_text_align  = (string) $core->blog->settings->origineConfig->content_text_align;
$content_hyphens     = (bool) $core->blog->settings->origineConfig->content_hyphens;

if (!empty($_POST)) {
  try {
    $activation          = !empty($_POST['activation']);
    $content_font_family = trim(html::escapeHTML($_POST['content_font_family']));
    $content_font_size   = abs((int) $_POST['content_font_size']);
    $content_text_align  = trim(html::escapeHTML($_POST['content_text_align']));
    $content_hyphens     = !empty($_POST['content_hyphens']);

    // Save.
    $core->blog->settings->addNamespace('origineConfig');

    $core->blog->settings->origineConfig->put('activation', $activation);
    $core->blog->settings->origineConfig->put('content_font_family', $content_font_family);
    $core->blog->settings->origineConfig->put('content_font_size', $content_font_size);
    $core->blog->settings->origineConfig->put('content_text_align', $content_text_align);
    $core->blog->settings->origineConfig->put('content_hyphens', $content_hyphens);

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
        <h3><?php echo __('Mise en forme du texte'); ?></h3>

        <div>
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
      </div>

      <p>
        <?php echo $core->formNonce(); ?>

        <input type="submit" value="<?php echo __('Save'); ?>" />
      </p>
    </form>
  </body>
</html>
