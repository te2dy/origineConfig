<?php
/**
 * origineConfig, a plugin to customize Origine, a Dotclear theme.
 *
 * @copyright Teddy
 * @copyright GPL-3.0
 */

if (!defined('DC_RC_PATH')) {
    return;
}

// Behaviors.
\dcCore::app()->addBehavior('publicHeadContent', ['origineConfig', 'origineMinimalSocialMarkups']);
\dcCore::app()->addBehavior('publicHeadContent', ['origineConfig', 'origineConfigMeta']);
//\dcCore::app()->addBehavior('publicHeadContent', ['origineConfig', 'origineConfigJsonData']);
\dcCore::app()->addBehavior('publicEntryBeforeContent', ['origineConfig', 'origineConfigPostIntro']);
\dcCore::app()->addBehavior('publicFooterContent', ['origineConfig', 'publicFooterSocialLinks']);
\dcCore::app()->addBehavior('publicEntryAfterContent', ['origineConfig', 'origineShareLinks']);
\dcCore::app()->addBehavior('publicEntryAfterContent', ['origineConfig', 'origineConfigImagesWide']);

// Values.
\dcCore::app()->tpl->addValue('origineConfigLogo', ['origineConfig', 'origineConfigLogo']);
\dcCore::app()->tpl->addValue('origineConfigEntriesAuthorName', ['origineConfig', 'origineConfigEntriesAuthorName']);
\dcCore::app()->tpl->addValue('origineConfigEntryAuthorNameNextToDate', ['origineConfig', 'origineConfigEntryAuthorNameNextToDate']);
\dcCore::app()->tpl->addValue('origineConfigEntryAuthorNameSignature', ['origineConfig', 'origineConfigEntryAuthorNameSignature']);
\dcCore::app()->tpl->addValue('origineConfigEmailAuthor', ['origineConfig', 'origineConfigEmailAuthor']);
\dcCore::app()->tpl->addValue('origineConfigEntryFirstImage', ['origineConfig', 'origineConfigEntryFirstImage']);
\dcCore::app()->tpl->addValue('origineConfigCommentLink', ['origineConfig', 'origineConfigCommentLink']);

//\dcCore::app()->addBehavior('publicTopAfterContent', ['origineConfig', 'origineConfigColorScheme']);

class origineConfig
{
    /**
     * Displays minimal social markups.
     *
     * @link https://meiert.com/en/blog/minimal-social-markup/
     *
     * @return void
     */
    public static function origineMinimalSocialMarkups()
    {
        $title = '';
        $desc    = '';
        $img     = '';

        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->global_meta_social === true) {
            // Posts and pages.
            if (\dcCore::app()->url->type === 'post' || \dcCore::app()->url->type === 'pages') {
                $title = \dcCore::app()->ctx->posts->post_title;
                $desc    = \dcCore::app()->ctx->posts->getExcerpt();

                if ($desc === '') {
                    $desc = \dcCore::app()->ctx->posts->getContent();
                }

                $desc = html::decodeEntities(html::clean($desc));
                $desc = preg_replace('/\s+/', ' ', $desc);
                $desc = html::escapeHTML($desc);

                if (strlen($desc) > 180) {
                    $desc = text::cutString($desc, 179) . '…';
                }

                if (context::EntryFirstImageHelper('o', true, '', true)) {
                    $img = context::EntryFirstImageHelper('o', true, '', true);
                }

            // Home.
            } elseif (\dcCore::app()->url->type === 'default' || \dcCore::app()->url->type === 'default-page') {
                $title = \dcCore::app()->blog->name;

                if (intval(context::PaginationPosition()) > 1 ) {
                    $desc = sprintf(
                        __('meta-social-page-with-number'),
                        context::PaginationPosition()
                    );
                }

                if (\dcCore::app()->blog->desc !== '') {
                    if ($desc !== '') {
                        $desc .= ' – ';
                    }

                    $desc .= \dcCore::app()->blog->desc;
                    $desc    = html::decodeEntities(html::clean($desc));
                    $desc    = preg_replace('/\s+/', ' ', $desc);
                    $desc    = html::escapeHTML($desc);

                    if (strlen($desc) > 180) {
                        $desc = text::cutString($desc, 179) . '…';
                    }
                }

            // Categories.
            } elseif (\dcCore::app()->url->type === 'category') {
                $title = \dcCore::app()->ctx->categories->cat_title;

                if (\dcCore::app()->ctx->categories->cat_desc !== '') {
                    $desc = \dcCore::app()->ctx->categories->cat_desc;
                    $desc = html::decodeEntities(html::clean($desc));
                    $desc = preg_replace('/\s+/', ' ', $desc);
                    $desc = html::escapeHTML($desc);

                    if (strlen($desc) > 180) {
                        $desc = text::cutString($desc, 179) . '…';
                    }
                }

            // Tags.
            } elseif (\dcCore::app()->url->type === 'tag' && \dcCore::app()->ctx->meta->meta_type === 'tag') {
                $title = \dcCore::app()->ctx->meta->meta_id;
                $desc    = sprintf(__('meta-social-tags-post-related'), $title);
            }

            $title = html::escapeHTML($title);
            $img     = html::escapeURL($img);

            if ($title) {
                if ($img) {
                    echo '<meta name=twitter:card content=summary_large_image>', "\n";
                }

                echo '<meta property=og:title content="', $title, '">', "\n";

                if ($desc) {
                    echo '<meta property="og:description" name="description" content="', $desc, '">', "\n";
                }

                if ($img) {
                    echo '<meta property="og:image" content="', $img, '">', "\n";
                }
            }
        }
    }

    /**
     * Display some meta markups in head.
     *
     * @return string Meta markups.
     */
    public static function origineConfigMeta()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true) {
            if (\dcCore::app()->blog->settings->origineConfig->global_meta_generator === true) {
                echo '<meta name=generator content=Dotclear>', "\n";
            }

            if (\dcCore::app()->blog->settings->origineConfig->global_meta_pingback === true) {
                if (\dcCore::app()->blog->url === 'post' && \dcCore::app()->ctx->posts->trackbacksActive() === true) {
                    echo '<link rel=pingback href=', html::escapeURL(\dcCore::app()->blog->url . \dcCore::app()->url->getURLFor('xmlrpc', \dcCore::app()->blog->id)), '>', "\n";
                }
            }
        }
    }

    /**
     * Adds JSON structured data in head.
     *
     * @return string Data.
     */
    public static function origineConfigJsonData()
    {
        // Posts and pages.
        if (\dcCore::app()->url->type === 'post' || \dcCore::app()->url->type === 'pages') {
            $data = [
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => \dcCore::app()->ctx->posts->post_title,
                'datePublished' => "2015-02-05T08:00:00+08:00",
                'dateModified' => "2015-02-05T09:20:00+08:00",
                'author' => [
                    '@type' => 'Person',
                    'name' => \dcCore::app()->ctx->posts->user_displayname,
                ],
            ];
        }

        if (isset($data) && !empty($data)) {
            echo '<script type="application/ld+json">', json_encode($data), '</script>';
        }
    }

    /**
     * Displays the excerpt as an introduction before post content.
     */
    public static function origineConfigPostIntro()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->content_post_intro === true && \dcCore::app()->ctx->posts->post_excerpt) {
            echo '<div id=post-single-excerpt>',
            \dcCore::app()->ctx->posts->getExcerpt(),
            '</div>';
        }
    }

    /**
     * Retrieves shapes of a spetific SVG icon from its name
     * or an array of SVG icon shpaes.
     *
     * @source Simple Icons
     * @link https://simpleicons.org/
     *
     * @param string $icon The name of the icon to retrieve.
     *
     * @return string|array The SVG shapes if $icon is set, or an array of all shapes.
     */
    private static function origineConfigSocialIcons($icon = '')
    {
        $social_svg = [];

        $social_svg['Diaspora'] = '<path d="M15.257 21.928l-2.33-3.255c-.622-.87-1.128-1.549-1.155-1.55-.027 0-1.007 1.317-2.317 3.115-1.248 1.713-2.28 3.115-2.292 3.115-.035 0-4.5-3.145-4.51-3.178-.006-.016 1.003-1.497 2.242-3.292 1.239-1.794 2.252-3.29 2.252-3.325 0-.056-.401-.197-3.55-1.247a1604.93 1604.93 0 0 1-3.593-1.2c-.033-.013.153-.635.79-2.648.46-1.446.845-2.642.857-2.656.013-.015 1.71.528 3.772 1.207 2.062.678 3.766 1.233 3.787 1.233.021 0 .045-.032.053-.07.008-.039.026-1.794.04-3.902.013-2.107.036-3.848.05-3.87.02-.03.599-.038 2.725-.038 1.485 0 2.716.01 2.735.023.023.016.064 1.175.132 3.776.112 4.273.115 4.33.183 4.33.026 0 1.66-.547 3.631-1.216 1.97-.668 3.593-1.204 3.605-1.191.04.045 1.656 5.307 1.636 5.327-.011.01-1.656.574-3.655 1.252-2.75.932-3.638 1.244-3.645 1.284-.006.029.94 1.442 2.143 3.202 1.184 1.733 2.148 3.164 2.143 3.18-.012.036-4.442 3.299-4.48 3.299-.015 0-.577-.767-1.249-1.705z">';

        $social_svg['Discord'] = '<path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z">';

        $social_svg['Facebook'] = '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z">';

        $social_svg['GitHub'] = '<path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12">';

        $social_svg['Mastodon'] = '<path d="M23.193 7.88c0-5.207-3.411-6.733-3.411-6.733C18.062.357 15.108.025 12.041 0h-.076c-3.069.025-6.02.357-7.74 1.147 0 0-3.412 1.526-3.412 6.732 0 1.193-.023 2.619.015 4.13.124 5.092.934 10.11 5.641 11.355 2.17.574 4.034.695 5.536.612 2.722-.15 4.25-.972 4.25-.972l-.09-1.975s-1.945.613-4.13.54c-2.165-.075-4.449-.234-4.799-2.892a5.5 5.5 0 0 1-.048-.745s2.125.52 4.818.643c1.646.075 3.19-.097 4.758-.283 3.007-.359 5.625-2.212 5.954-3.905.517-2.665.475-6.508.475-6.508zm-4.024 6.709h-2.497v-6.12c0-1.29-.543-1.944-1.628-1.944-1.2 0-1.802.776-1.802 2.313v3.349h-2.484v-3.35c0-1.537-.602-2.313-1.802-2.313-1.085 0-1.628.655-1.628 1.945v6.119H4.831V8.285c0-1.29.328-2.314.987-3.07.68-.759 1.57-1.147 2.674-1.147 1.278 0 2.246.491 2.886 1.474L12 6.585l.622-1.043c.64-.983 1.608-1.474 2.886-1.474 1.104 0 1.994.388 2.674 1.146.658.757.986 1.781.986 3.07v6.305z">';

        $social_svg['Signal'] = '<path d="m9.12.35.27 1.09a10.845 10.845 0 0 0-3.015 1.248l-.578-.964A11.955 11.955 0 0 1 9.12.35zm5.76 0-.27 1.09a10.845 10.845 0 0 1 3.015 1.248l.581-.964A11.955 11.955 0 0 0 14.88.35zM1.725 5.797A11.955 11.955 0 0 0 .351 9.119l1.09.27A10.845 10.845 0 0 1 2.69 6.374zm-.6 6.202a10.856 10.856 0 0 1 .122-1.63l-1.112-.168a12.043 12.043 0 0 0 0 3.596l1.112-.169A10.856 10.856 0 0 1 1.125 12zm17.078 10.275-.578-.964a10.845 10.845 0 0 1-3.011 1.247l.27 1.091a11.955 11.955 0 0 0 3.319-1.374zM22.875 12a10.856 10.856 0 0 1-.122 1.63l1.112.168a12.043 12.043 0 0 0 0-3.596l-1.112.169a10.856 10.856 0 0 1 .122 1.63zm.774 2.88-1.09-.27a10.845 10.845 0 0 1-1.248 3.015l.964.581a11.955 11.955 0 0 0 1.374-3.326zm-10.02 7.875a10.952 10.952 0 0 1-3.258 0l-.17 1.112a12.043 12.043 0 0 0 3.597 0zm7.125-4.303a10.914 10.914 0 0 1-2.304 2.302l.668.906a12.019 12.019 0 0 0 2.542-2.535zM18.45 3.245a10.914 10.914 0 0 1 2.304 2.304l.906-.675a12.019 12.019 0 0 0-2.535-2.535zM3.246 5.549A10.914 10.914 0 0 1 5.55 3.245l-.675-.906A12.019 12.019 0 0 0 2.34 4.874zm19.029.248-.964.577a10.845 10.845 0 0 1 1.247 3.011l1.091-.27a11.955 11.955 0 0 0-1.374-3.318zM10.371 1.246a10.952 10.952 0 0 1 3.258 0L13.8.134a12.043 12.043 0 0 0-3.597 0zM3.823 21.957 1.5 22.5l.542-2.323-1.095-.257-.542 2.323a1.125 1.125 0 0 0 1.352 1.352l2.321-.532zm-2.642-3.041 1.095.255.375-1.61a10.828 10.828 0 0 1-1.21-2.952l-1.09.27a11.91 11.91 0 0 0 1.106 2.852zm5.25 2.437-1.61.375.255 1.095 1.185-.275a11.91 11.91 0 0 0 2.851 1.106l.27-1.091a10.828 10.828 0 0 1-2.943-1.217zM12 2.25a9.75 9.75 0 0 0-8.25 14.938l-.938 4 4-.938A9.75 9.75 0 1 0 12 2.25z">';

        $social_svg['TikTok'] = '<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z">';

        $social_svg['Twitter'] = '<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z">';

        $social_svg['WhatsApp'] = '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z">';

        if ($icon && array_key_exists($icon, $social_svg)) {
            return $social_svg[$icon];
        } else {
            return $social_svg;
        }
    }

    /**
     * Displays social links in the footer.
     */
    public static function publicFooterSocialLinks()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true) {
            $social_links = [];

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_diaspora) {
                $social_links['Diaspora'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_diaspora;
            }

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_discord) {
                $social_links['Discord'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_discord;
            }

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_facebook) {
                $social_links['Facebook'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_facebook;
            }

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_github) {
                $social_links['GitHub'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_github;
            }

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_mastodon) {
                $social_links['Mastodon'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_mastodon;
            }

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_signal) {
                $social_links['Signal'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_signal;
            }

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_tiktok) {
                $social_links['TikTok'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_tiktok;
            }

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_twitter) {
                $social_links['Twitter'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_twitter;
            }

            if (\dcCore::app()->blog->settings->origineConfig->footer_social_links_whatsapp) {
                $social_links['WhatsApp'] = \dcCore::app()->blog->settings->origineConfig->footer_social_links_whatsapp;
            }

            if (!empty($social_links)) {
                ?>

                <div class="site-footer-line footer-social-links">
                    <ul>
                        <?php
                        foreach ($social_links as $site => $link) {
                            if ($site === 'Signal') {
                                if (substr($link, 0, 1) === '+') {
                                    $link = 'https://signal.me/#p/' . $link;
                                } else {
                                    $link = $link;
                                }
                            } elseif ($site === 'Twitter') {
                                $link = 'https://twitter.com/' . str_replace('@', '', $link);
                            }
                            ?>

                            <li>
                                <a href="<?php echo html::escapeURL($link); ?>">
                                    <span class=footer-social-links-icon-container>
                                        <svg class=footer-social-links-icon role=img viewBox="0 0 24 24" xmlns=http://www.w3.org/2000/svg>
                                            <title><?php echo html::escapeHTML($site); ?></title>

                                            <?php echo strip_tags(self::origineConfigSocialIcons($site), '<path>'); ?>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
        }
    }

    /**
     * Displays links to share the current post.
     *
     * ADD AN OPTION TO DISPLAY THEM ONLY ON DESKTOP (NOT NEEDED ON MOBILE AS A BUTTON ALWAYS EXISTS TO SHARE PAGES).
     */
    /*public static function origineShareLinks()
    {
        if (\dcCore::app()->blog->settings->origineConfig->origine_settings['content_share_link_twitter'] === true) {
            // An array of allowed sites or services to share with.
            $sites_allowed = [
                'email'        => __('Email'),
                'facebook' => __('Facebook'),
                'print'        => __('Print'),
                'whatsapp' => __('WhatsApp'),
                'twitter'    => __('Twitter'),
            ];

            // Sort the previous array in the alphabetical order of its translated values.
            asort($sites_allowed);
            */

            /**
             * If the option is checked in the plugin settings,
             * create a value (the URL) with its associative key (the name of the site or service)
             * for each site or service to share with.
             */
            /*

            $networks_url = [];

            $post_url     = \dcCore::app()->ctx->posts->getURL()     ? \dcCore::app()->ctx->posts->getURL()     : '';
            $post_title = \dcCore::app()->ctx->posts->post_title ? \dcCore::app()->ctx->posts->post_title : '';

            // Email.
            if (\dcCore::app()->blog->settings->origineConfig->origine_settings['content_share_link_email'] === true) {
                if ($post_url !== '') {
                    $networks_url['email'] = 'mailto:?body=' . $post_url;

                    if ($post_title !== '') {
                        $networks_url['email'] .= '&subject=' . $post_title;
                    }
                }
            }

            // Facebook.
            if (\dcCore::app()->blog->settings->origineConfig->origine_settings['content_share_link_facebook'] === true) {
                if ($post_url !== '') {
                    $networks_url['facebook'] = 'https://www.facebook.com/sharer.php?u' . $post_url;
                }
            }

            // Print.
            if (\dcCore::app()->blog->settings->origineConfig->origine_settings['content_share_link_print'] === true) {
                if ($post_url !== '') {
                    $networks_url['print'] = 'javascript:window.print();';
                }
            }

            // WhatsApp.
            if (\dcCore::app()->blog->settings->origineConfig->origine_settings['content_share_link_whatsapp'] === true) {
                if ($post_url !== '') {
                    $networks_url['whatsapp'] = 'https://api.whatsapp.com/send?';

                    if ($post_title !== '') {
                        $networks_url['whatsapp'] = 'text=' . $post_title . '&';
                    }

                    $networks_url['whatsapp'] .= 'url=' . $post_url;
                }
            }

            // Twitter.
            if (\dcCore::app()->blog->settings->origineConfig->origine_settings['content_share_link_twitter'] === true) {
                if ($post_url !== '') {
                    $networks_url['twitter'] = 'https://twitter.com/share?url=' . $post_url;

                    if ($post_title !== '') {
                        $networks_url['twitter'] .= '&text=' . $post_title;
                    }
                }
            }

            $output = '';

            // If there are values in the array, displays the buttons.
            if (!empty($networks_url)) {
                $networks_count = count($networks_url);
                ?>

                <p class=share-links>
                    <?php echo __('Share:'); ?>

                    <?php
                    $i = 0;
                    foreach($sites_allowed as $site_nicename => $site_name) {
                        if (array_key_exists($site_nicename, $networks_url) === true) {
                            $i++;
                            ?>

                            <a href="<?php echo html::escapeURL($networks_url[$site_nicename]); ?>">
                                <?php echo html::escapeHTML($site_name); ?>
                            </a>

                            <?php
                            if ($i < $networks_count) {
                                echo ' / ';
                            }
                        }
                    }
                    ?>
                </p>

                <?php
            }
        }
    }
    */

    /**
     * Displays wide images.
     *
     * If the image width is wider than the page width, the image will overflow to be displayed bigger.
     *
     * @return void
     */
    public static function origineConfigImagesWide()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->content_images_wide === true) {
                $page_width_em = \dcCore::app()->blog->settings->origineConfig->global_page_width ? \dcCore::app()->blog->settings->origineConfig->global_page_width : 30;
                ?>
                    <script>
                        var pageWidthEm = parseInt(<?php echo $page_width_em; ?>),
                            imgWideWidthEm = pageWidthEm + 10,
                            pageWidthPx = 0, // To set later.
                            imgWideWidthPx = 0, // To set later.
                            fontSize = 0; // To set later.

                        /**
                         * Gets the font size defined by the browser.
                         *
                         * @link https://brokul.dev/detecting-the-default-browser-font-size-in-javascript
                         */
                        const element = document.createElement('div');
                        element.style.width = '1rem';
                        element.style.display = 'none';
                        document.body.append(element);

                        const widthMatch = window.getComputedStyle(element).getPropertyValue('width').match(/\d+/);

                        element.remove();

                        // Sets the font size in px.
                        if (widthMatch && widthMatch.length >= 1) {
                            fontSizePx = parseInt(widthMatch[0]);
                        }

                        // If a font size is set, calculates page and image width in px.
                        if (fontSizePx > 0) {
                            pageWidthPx = pageWidthEm * fontSizePx;
                            imgWideWidthPx = imgWideWidthEm * fontSizePx;
                        }

                        function getMeta(url, callback) {
                            var img = new Image();
                            img.src = url;
                            img.onload = function() {
                                callback(this.width, this.height);
                            }
                        }

                        // Gets all images in the post.
                        var img = document.getElementsByTagName("article")[0].getElementsByTagName("img"),
                            i = 0;

                        // Expands each image.
                        while (i < img.length) {
                            let myImg = img[i];

                                getMeta(
                                    myImg.src,
                                function(width, height) {
                                    let imgWidth = width,
                                        imgHeight = height;

                                    // Applies expand styles only to lanscape images.
                                    if (imgWidth > pageWidthPx && (imgWidth > imgHeight)) {
                                        if (imgWidth > imgWideWidthPx) {
                                            imgHeight = parseInt(imgWideWidthPx * imgHeight / imgWidth);
                                            imgWidth = imgWideWidthPx;
                                        }

                                        // Defines the styles.
                                        let myImgStyle = "display:block; margin-left: 50%; transform: translateX(-50%); max-width: 100vw;";

                                        // Sets the image attributes.
                                        myImg.setAttribute("style", myImgStyle);
                                        myImg.setAttribute("width", imgWidth);
                                        myImg.setAttribute("height", imgHeight);
                                    }
                                }
                            );

                            i++;
                        }
                </script>
            <?php
        }
    }

    /**
     * Displays a logo in the header.
     */
    public static function origineConfigLogo($attr)
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->header_logo_url) {
            $src_image = \dcCore::app()->blog->settings->origineConfig->header_logo_url ? html::escapeURL(\dcCore::app()->blog->settings->origineConfig->header_logo_url) : '';
            $srcset        = '';

            $url_public_relative = \dcCore::app()->blog->settings->system->public_url;
            $public_path                 = \dcCore::app()->blog->public_path;

            if ($src_image !== '') {
                $image_url_relative = substr($src_image, strpos($src_image, $url_public_relative));

                $image_path = $public_path . str_replace($url_public_relative . '/', '/', $image_url_relative);

                if (\dcCore::app()->blog->settings->origineConfig->header_logo_url_2x && \dcCore::app()->blog->settings->origineConfig->header_logo_url_2x !== $src_image) {
                    $src_image_2x = html::escapeURL(\dcCore::app()->blog->settings->origineConfig->header_logo_url_2x);
                } else {
                    $src_image_2x = '';
                }

                if ($src_image_2x !== '') {
                    $srcset = ' srcset="' . $src_image . ' 1x, ' . $src_image_2x . ' 2x"';
                }

                if (getimagesize($image_path)) {
                    $image_width    = (int) getimagesize($image_path)[0];
                    $image_height = (int) getimagesize($image_path)[1];
                } else {
                    $image_width    = 0;
                    $image_height = 0;
                }

                $image_size_attr    = '';

                if ($image_width > 0 && $image_height > 0) {
                    if ($image_width <= 480) {
                        $image_size_attr .= ' width="' . $image_width . '" height="' . $image_height . '"';
                    } else {
                        $image_size_attr .= ' width="480" height="' . $image_height * (480 / $image_width) . '"';

                        if ($src_image_2x !== '') {
                            $image_size_attr .= ' sizes="100vw"';
                        }
                    }
                }

                if (isset($attr['link_home']) && $attr['link_home'] === '1') {
                    $link_open    = '<a class=site-logo-link href=' . html::escapeURL(\dcCore::app()->blog->url) . '>';
                    $link_close = '</a>';
                } else {
                    $link_open    = '';
                    $link_close = '';
                }
            }

            return '<div class=site-logo-container>' . $link_open . '<img alt=' . __('logo-img-alt') . ' class=site-logo src=' . $src_image . $srcset . $image_size_attr . '>' . $link_close . '</div>';
        }
    }

    /**
     * Displays the author name of the post after the date.
     */
    public static function origineConfigEntryAuthorNameNextToDate()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->content_post_author_name === 'date') {

            $output = '<?php if (\dcCore::app()->ctx->posts->user_displayname || \dcCore::app()->ctx->posts->user_firstname || \dcCore::app()->ctx->posts->user_name ) : ?>';

            $output .= ' <span class=post-author-name rel=author>';
            $output .= \dcCore::app()->blog->settings->origineConfig->global_separator . ' ';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_url) {';
            $output .= 'echo "<a href=\"" . \dcCore::app()->ctx->posts->user_url . "\">";';
            $output .= '} ?>';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_displayname) {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_displayname;';
            $output .= '} elseif (\dcCore::app()->ctx->posts->user_firstname) {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_firstname;';
            $output .= 'if (\dcCore::app()->ctx->posts->user_name) {';
            $output .= 'echo " " . \dcCore::app()->ctx->posts->user_name;';
            $output .= '} } else {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_name ? \dcCore::app()->ctx->posts->user_name : "";';
            $output .= '} ?>';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_url) {';
            $output .= 'echo "</a>";';
            $output .= '} ?>';

            $output .= '</span>';

            $output .= '<?php endif; ?>';

            return $output;
        }
    }

    /**
     * Displays the author name in the post list.
     */
    public static function origineConfigEntriesAuthorName()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->content_post_list_author_name === true) {
            $output = '<?php if (\dcCore::app()->ctx->posts->user_displayname || \dcCore::app()->ctx->posts->user_firstname || \dcCore::app()->ctx->posts->user_name ) : ?>';

            $output .= ' <span class="post-author-name" rel="author">';
            $output .= \dcCore::app()->blog->settings->origineConfig->global_separator . ' ';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_url) {';
            $output .= 'echo "<a href=\"" . \dcCore::app()->ctx->posts->user_url . "\">";';
            $output .= '} ?>';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_displayname) {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_displayname;';
            $output .= '} elseif (\dcCore::app()->ctx->posts->user_firstname) {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_firstname;';
            $output .= 'if (\dcCore::app()->ctx->posts->user_name) {';
            $output .= 'echo " " . \dcCore::app()->ctx->posts->user_name;';
            $output .= '} } else {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_name ? \dcCore::app()->ctx->posts->user_name : "";';
            $output .= '} ?>';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_url) {';
            $output .= 'echo "</a>";';
            $output .= '} ?>';

            $output .= '</span>';

            $output .= '<?php endif; ?>';

            return $output;
        }
    }

    /**
     * Displays the author name under the post as a signature.
     */
    public static function origineConfigEntryAuthorNameSignature()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->content_post_author_name === 'signature') {
            $output = '<?php if (\dcCore::app()->ctx->posts->user_displayname || \dcCore::app()->ctx->posts->user_firstname || \dcCore::app()->ctx->posts->user_name ) : ?>';

            $output .= '<p class="post-author-name" rel="author">';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_url) {';
            $output .= 'echo "<a href=\"" . \dcCore::app()->ctx->posts->user_url . "\">";';
            $output .= '} ?>';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_displayname) {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_displayname;';
            $output .= '} elseif (\dcCore::app()->ctx->posts->user_firstname) {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_firstname;';
            $output .= 'if (\dcCore::app()->ctx->posts->user_name) {';
            $output .= 'echo " " . \dcCore::app()->ctx->posts->user_name;';
            $output .= '} } else {';
            $output .= 'echo \dcCore::app()->ctx->posts->user_name ? \dcCore::app()->ctx->posts->user_name : "";';
            $output .= '} ?>';

            $output .= '<?php if (\dcCore::app()->ctx->posts->user_url) {';
            $output .= 'echo "</a>";';
            $output .= '} ?>';

            $output .= '</p>';

            $output .= '<?php endif; ?>';

            return $output;
        }
    }

    /**
     * Displays a link to reply to the author of the post by email.
     */
    public static function origineConfigEmailAuthor()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->content_post_email_author !== 'disabled') {

                $output = '<?php if (isset(\dcCore::app()->ctx->posts->user_email) && (\dcCore::app()->blog->settings->origineConfig->content_post_email_author === "always" || (\dcCore::app()->blog->settings->origineConfig->content_post_email_author === "comments_open" && \dcCore::app()->ctx->posts->post_open_comment === "1"))) : ?>';

            $output .= '<div class=comment-private>';

            $output .= '<h3 class=reaction-title>' . __('private-comment-title') . '</h3>';

            $output .= '<p>';
            $output .= '<a class=button href="mailto:<?php echo urlencode(\dcCore::app()->ctx->posts->user_email); ?>';
            $output .= '?subject=<?php echo htmlentities(__("private-comment-email-prefix") . " " . \dcCore::app()->ctx->posts->post_title, ENT_NOQUOTES); ?>';
            $output .= '">';
            $output .= __('private-comment-button-text');
            $output .= '</a>';
            $output .= '</p>';

            $output .= '</div>';

            $output .= '<?php endif; ?>';

            return $output;
        }
    }

    /**
     * Displays the first image of a post.
     *
     * IN DEVELOPMENT.
     */
    public static function origineConfigEntryFirstImage($attr)
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->content_post_list_first_image === true) {
            $url_public_relative = \dcCore::app()->blog->settings->system->public_url;
            $public_path                 = \dcCore::app()->blog->public_path;

            return '
                <?php
                // The image URL.
                $img_o_url = context::EntryFirstImageHelper(addslashes("o"), 0, "", true);

                // The image tag and its parameters.
                $img_o = context::EntryFirstImageHelper(addslashes("o"), 0, "", false);

                // Gets the value of alt.
                if (preg_match(\'/alt="([^"]+)"/\', $img_o, $alt)) {
                    $img_o_alt = " alt=\"" . $alt[1] . "\"";
                } else {
                    $img_o_alt = "";
                }

                $img_o_only = true;

                if ($img_o_url) {
                    $img_m_url = context::EntryFirstImageHelper(addslashes("m"), 0, "", true);
                    $img_s_url = context::EntryFirstImageHelper(addslashes("s"), 0, "", true);

                    $img_o_path     = "' . $public_path . '" . str_replace("' . $url_public_relative . '" . "/", "/", $img_o_url);
                    $img_o_width    = (int) getimagesize($img_o_path)[0];
                    $img_o_height = (int) getimagesize($img_o_path)[1];

                    $images            = [];
                    $images["o"] = ["$img_o_url", $img_o_width];

                    $img_m_width = 0;
                    $img_s_width = 0;

                    if ($img_o_url !== $img_m_url && $img_o_url !== $img_s_url) {
                        $img_o_only = false;

                        if ($img_o_url !== $img_m_url) {
                            $img_m_path    = "' . $public_path . '" . str_replace("' . $url_public_relative . '" . "/", "/", $img_m_url);
                            $img_m_width = (int) getimagesize($img_m_path)[0];
                        }

                        if ($img_o_url !== $img_s_url) {
                            $img_s_path    = "' . $public_path . '" . str_replace("' . $url_public_relative . '" . "/", "/", $img_s_url);
                            $img_s_width = (int) getimagesize($img_s_path)[0];
                        }

                        if ($img_m_width > 0) {
                            $images["m"] = ["$img_m_url", $img_m_width];
                        }

                        if ($img_s_width > 0) {
                            $images["s"] = ["$img_s_url", $img_s_width];
                        }

                        array_reverse($images);
                    }

                    $src_set_value = "";
                    $sizes_attr        = "";

                    if (isset($images) && count($images) > 1) {
                        $src_set_value .= " srcset=\"";

                        $sizes_array = [];

                        $i = 0;
                        foreach ($images as $size => $data) {
                            $src_set_value .= "$data[0] $data[1]w";

                            if (array_key_last($images) !== $size) {
                                $src_set_value .= ", ";
                            }
                        }

                        $src_set_value .= "\" sizes=\"100vw\"";
                    }
                    ?>

                    <img<?php echo $img_o_alt; ?> class="post-thumbnail" loading="lazy" src="<?php echo $img_o_url; ?>"<?php echo $src_set_value; ?>>

                    <?php
                }
                ?>
            ';
        }
    }

    /**
     * Displays a link to comments in the post list.
     *
     * Only if a least a comment has been published.
     *
     * @return void
     */
    public static function origineConfigCommentLink()
    {
        if (\dcCore::app()->blog->settings->origineConfig->active === true && \dcCore::app()->blog->settings->origineConfig->content_post_list_comment_link === true) {
            return '
                <?php
                if ((int) \dcCore::app()->ctx->posts->nb_comment > 0) {
                    echo "<a aria-label=\"";

                    if ((int) \dcCore::app()->ctx->posts->nb_comment > 1) {
                        printf(__("post-list-multiple-reactions-link-aria-label"), \dcCore::app()->ctx->posts->nb_comment);
                    } else {
                        echo __("post-list-one-reaction-link-aria-label");
                    }

                    echo "\" class=post-comment-link href=" . \dcCore::app()->ctx->posts->getURL() . "#" . __("reactions-id") . ">",
                    "[" . \dcCore::app()->ctx->posts->nb_comment . "]</a>";
                };
                ?>';
        }
    }

    /**
     * TEST
     */
    public static function origineConfigColorScheme()
    {
        ?>
            <script>
                var theme = "light";

                // local storage is used to override OS theme settings
                if(localStorage.getItem("theme")){
                    if(localStorage.getItem("theme") == "dark"){
                        var theme = "dark";
                    }
                } else if(!window.matchMedia) {
                    //matchMedia method not supported
                    return false;
                } else if(window.matchMedia("(prefers-color-scheme: dark)").matches) {
                    //OS theme setting detected as dark
                    var theme = "dark";
                }

                //dark theme preferred, set document with a `data-theme` attribute
                if (theme=="dark") {
                    document.documentElement.setAttribute("data-theme", "dark");
                }

                function toggleColorScheme() {
                    if (document.documentElement.classList.contains("light")) {
                        document.documentElement.classList.remove("light");
                            document.documentElement.classList.add("dark");
                    } else if (document.documentElement.classList.contains("dark")) {
                        document.documentElement.classList.remove("dark");
                        document.documentElement.classList.add("light");
                    } else {
                        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                            document.documentElement.classList.add("light");
                        } else {
                            document.documentElement.classList.add("dark");
                        }
                    }
                }
            </script>

            <button id=color-scheme-icon onclick=toggleColorScheme()>Couleurs</button>

            <script>
                document.getElementById("color-scheme-icon").onload = function(){
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        localStorage.setItem("origine_mini_color_scheme", "dark");
                    } else {
                        localStorage.setItem("origine_mini_color_scheme", "light");
                    }
                };

                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add("dark");
                } else {
                    document.documentElement.classList.add("light");
                }

                /*
                // If The input is empty or its value is equal to the search, disables the submit button.
                if (document.getElementById("search-form-input").value && document.getElementById("search-form-input").value !== searchQuery) {
                    document.getElementById("search-submit").disabled = false;
                } else {
                    document.getElementById("search-submit").disabled = true;
                }*/
            </script>

            <!-- Lire https://stackoverflow.com/questions/56300132/how-to-override-css-prefers-color-scheme-setting-->
        <?php
    }
}
