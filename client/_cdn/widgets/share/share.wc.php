<?php

if (empty($WcSocialRequired)):
    $WcSocialRequired = true;
    echo "<link rel='stylesheet' href='" . BASE . "/_cdn/widgets/share/share.wc.css'/>";
    echo "<script src='" . BASE . "/_cdn/widgets/share/share.wc.js'></script>";
endif;

echo "<ul class='workcontrol_socialshare'>";
echo "<li class='workcontrol_socialshare_cta'><b>Compartilhe</b> esse post</li>";

$WcShareText = (!empty($WC_TITLE_LINK) ? $WC_TITLE_LINK : null);
$WcShareLink = (!empty($WC_SHARE_LINK) ? $WC_SHARE_LINK : BASE);
$WcShareHash = (!empty($WC_SHARE_HASH) ? $WC_SHARE_HASH : Check::Name(SITE_NAME));
/*
 * FACEBOOK
 */
$ShareIconText = 'Compartilhar no Facebook';
echo "<li class='workcontrol_socialshare_item workcontrol_socialshare_facebook'><a rel='{$WcShareLink}' target='_blank' title='{$ShareIconText}' href='https://www.facebook.com/sharer/sharer.php?u={$WcShareLink}'><img alt='{$ShareIconText}' title='{$ShareIconText}' src='" . BASE . "/_cdn/widgets/share/icons/facebook.png'/><span class='wc_fb_count'>00</span></a></li>";
/*
 * GOOGLE +
 */
$ShareIconText = 'Compartilhar no Google Plus';
echo "<li class='workcontrol_socialshare_item workcontrol_socialshare_googleplus'><a rel='{$WcShareLink}' target='_blank' title='{$ShareIconText}' href='https://plus.google.com/share?url={$WcShareLink}'><img alt='{$ShareIconText}' title='{$ShareIconText}' src='" . BASE . "/_cdn/widgets/share/icons/googleplus.png'/><span class='wc_gp_count'>00</span></a></li>";
/*
 * TWITTER
 */
$ShareIconText = 'Compartilhar no Twitter';
echo "<li class='workcontrol_socialshare_item workcontrol_socialshare_twitter'><a rel='{$WcShareLink}' target='_blank' title='{$ShareIconText}' href='https://twitter.com/intent/tweet?hashtags={$WcShareHash}&url={$WcShareLink}&text={$WcShareText}'><img alt='{$ShareIconText}' title='{$ShareIconText}' src='" . BASE . "/_cdn/widgets/share/icons/twitter.png'/></a></li>";

echo "</ul>";
