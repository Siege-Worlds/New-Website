<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$API_BASE = 'https://siegeworlds-f987f035dda9.herokuapp.com';

// LightningWorks SSO config
// Change to 'https://sso.lightningworks.io' for production
$SSO_BASE = 'http://localhost:3000';
$SSO_APP = 'siegeworlds';
// Change to 'https://siegeworlds.com/auth/callback' for production
$SSO_REDIRECT = 'http://localhost:8800/auth/callback.php';

function head()
{
    include 'component/c_head.php';
}

function header_nav()
{
    include 'component/c_header-nav.php';
}

function page_hero()
{
    include 'component/c_page-hero.php';
}

function quote()
{
    include 'component/c_quote.php';
}

function content_center_button()
{
    include 'component/c_content-center-button.php';
}

function content_image_right($title1, $title2, $bodytext, $button_text, $button_url)
{
    include 'component/c_content-image-right.php';
}

function partnerships()
{
    include 'component/c_partnerships.php';
}

function trailer_video()
{
    include 'component/c_trailer-video.php';
}

function image_section()
{
    include 'component/c_image-section.php';
}

function footer_branding()
{
    include 'component/c_footer-branding.php';
}

function footer_copyright()
{
    include 'component/c_footer-copyright.php';
}

function highlight_stats()
{
    include 'component/c_highlight-stats.php';
}

function roadmap()
{
    include 'component/c_roadmap.php';
}

function chat_shiyang()
{
    include 'component/c_chat-shiyang.php';
}

function is_logged_in()
{
    return !empty($_SESSION['logged_in']);
}

function is_admin()
{
    return !empty($_SESSION['admin_logged_in'])
        || (isset($_SESSION['roles']) && (in_array('admin', $_SESSION['roles']) || in_array('superadmin', $_SESSION['roles'])));
}

function render_avatar($size = 48)
{
    $avatarUrl = $_SESSION['avatar_url'] ?? '';
    $outerColor = $_SESSION['avatar_outer_color'] ?? '#000000';
    $innerColor = $_SESSION['avatar_inner_color'] ?? '#000000';
    $panX = $_SESSION['avatar_pan_x'] ?? 0.5;
    $panY = $_SESSION['avatar_pan_y'] ?? 0.5;
    $zoom = $_SESSION['avatar_zoom'] ?? 1.0;
    $name = $_SESSION['display_name'] ?? $_SESSION['username'] ?? '?';

    $ring = max(2, round($size * 0.03));
    $gap = max(1, round($size * 0.01));
    $inset = $ring + $gap + $ring;
    $imgSize = $size - $inset * 2;
    $tx = ($panX - 0.5) * -100;
    $ty = ($panY - 0.5) * -100;

    echo '<div style="width:'.$size.'px;height:'.$size.'px;border-radius:50%;position:relative;flex-shrink:0;">';
    echo '<div style="position:absolute;inset:0;border-radius:50%;background:'.$outerColor.';"></div>';
    echo '<div style="position:absolute;top:'.$ring.'px;left:'.$ring.'px;right:'.$ring.'px;bottom:'.$ring.'px;border-radius:50%;background:#000;"></div>';
    echo '<div style="position:absolute;top:'.($ring+$gap).'px;left:'.($ring+$gap).'px;right:'.($ring+$gap).'px;bottom:'.($ring+$gap).'px;border-radius:50%;background:'.$innerColor.';"></div>';
    echo '<div style="position:absolute;top:'.$inset.'px;left:'.$inset.'px;width:'.$imgSize.'px;height:'.$imgSize.'px;border-radius:50%;overflow:hidden;background:#1a1a1c;">';
    if ($avatarUrl) {
        echo '<img src="'.htmlspecialchars($avatarUrl).'" referrerpolicy="no-referrer" crossorigin="anonymous" style="width:100%;height:100%;object-fit:cover;transform:scale('.$zoom.') translate('.$tx.'%,'.$ty.'%);transform-origin:center;pointer-events:none;" />';
    } else {
        echo '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#7a7572;font-size:'.round($size*0.3).'px;">'.strtoupper(substr($name,0,1)).'</div>';
    }
    echo '</div></div>';
}
