<?php
defined('_JEXEC') or die;

// Slider Einstellungen
$position    = $params->get('slider_position', 'center');
$width       = (int) $params->get('slider_width', 100) . '%';
$height      = $params->get('slider_height', '400px');
$interval    = (int) $params->get('slide_interval', 5000);
$transition  = (int) $params->get('transition_speed', 500);
$autoplay    = (bool) $params->get('autoplay', 1);
$pauseHover  = (bool) $params->get('pause_hover', 1);
$showNav     = (bool) $params->get('show_nav', 1);
$showDots    = (bool) $params->get('show_dots', 1);

// Bildtext
$showTitle   = (bool) $params->get('show_title', 1);
$titlePos    = $params->get('title_position', 'bottom-left');

// Styling
$radius      = $params->get('border_radius', '0px');
$navColor    = $params->get('nav_color', '#ffffff');
$navBg       = $params->get('nav_bg', '#000000');
$dotColor    = $params->get('dot_color', '#cccccc');
$dotActive   = $params->get('dot_active', '#0066cc');
$titleColor  = $params->get('title_color', '#ffffff');
$titleBg     = $params->get('title_bg', '#000000');
$titleOpacity = (int) $params->get('title_opacity', 70);

// Berechnungen
$count = count($images);

// Ausrichtung
$alignments = [
    'left'   => 'margin:0 auto 20px 0;',
    'right'  => 'margin:0 0 20px auto;',
    'center' => 'margin:0 auto 20px auto;',
];
$align = $alignments[$position] ?? $alignments['center'];

// Titel Position CSS
$titlePositions = [
    'top-left'      => 'top:15px;left:15px;',
    'top-center'    => 'top:15px;left:50%;transform:translateX(-50%);',
    'top-right'     => 'top:15px;right:15px;',
    'bottom-left'   => 'bottom:15px;left:15px;',
    'bottom-center' => 'bottom:15px;left:50%;transform:translateX(-50%);',
    'bottom-right'  => 'bottom:15px;right:15px;',
];
$titlePosCSS = $titlePositions[$titlePos] ?? $titlePositions['bottom-left'];

// Hex zu RGBA
function modSliderHexRgba($hex, $opacity) {
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "rgba($r,$g,$b,$opacity)";
}

$titleBgRgba = modSliderHexRgba($titleBg, $titleOpacity / 100);
$navBgRgba = modSliderHexRgba($navBg, 0.5);
$navBgHover = modSliderHexRgba($navBg, 0.8);
?>

<style>
#<?php echo $sliderId; ?> {
    position: relative;
    width: <?php echo $width; ?>;
    max-width: 100%;
    <?php echo $align; ?>
    overflow: hidden;
    border-radius: <?php echo $radius; ?>;
}
#<?php echo $sliderId; ?> .slider-wrap {
    position: relative;
    width: 100%;
    height: <?php echo $height; ?>;
}
#<?php echo $sliderId; ?> .slide {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    opacity: 0;
    transition: opacity <?php echo $transition; ?>ms ease;
    z-index: 1;
}
#<?php echo $sliderId; ?> .slide.active {
    opacity: 1;
    z-index: 2;
}
#<?php echo $sliderId; ?> .slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
#<?php echo $sliderId; ?> .slide a {
    display: block;
    width: 100%;
    height: 100%;
}
#<?php echo $sliderId; ?> .slide-title {
    position: absolute;
    <?php echo $titlePosCSS; ?>
    background: <?php echo $titleBgRgba; ?>;
    color: <?php echo $titleColor; ?>;
    padding: 10px 18px;
    border-radius: 4px;
    font-size: 1.1em;
    z-index: 5;
    max-width: 80%;
}
#<?php echo $sliderId; ?> .nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: <?php echo $navBgRgba; ?>;
    color: <?php echo $navColor; ?>;
    border: none;
    padding: 15px 12px;
    cursor: pointer;
    font-size: 18px;
    z-index: 10;
    transition: background 0.3s;
    border-radius: 3px;
}
#<?php echo $sliderId; ?> .nav-btn:hover {
    background: <?php echo $navBgHover; ?>;
}
#<?php echo $sliderId; ?> .nav-prev { left: 10px; }
#<?php echo $sliderId; ?> .nav-next { right: 10px; }
#<?php echo $sliderId; ?> .dots {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
}
#<?php echo $sliderId; ?> .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: <?php echo $dotColor; ?>;
    border: 2px solid <?php echo $dotActive; ?>;
    cursor: pointer;
    padding: 0;
    transition: background 0.3s;
}
#<?php echo $sliderId; ?> .dot:hover,
#<?php echo $sliderId; ?> .dot.active {
    background: <?php echo $dotActive; ?>;
}
</style>

<div id="<?php echo $sliderId; ?>">
    <div class="slider-wrap">
        <?php foreach ($images as $i => $img): ?>
        <div class="slide<?php echo $i === 0 ? ' active' : ''; ?>">
            <?php if (!empty($img['link'])): ?>
            <a href="<?php echo htmlspecialchars($img['link']); ?>">
                <img src="<?php echo htmlspecialchars($img['src']); ?>" alt="<?php echo htmlspecialchars($img['alt']); ?>">
            </a>
            <?php else: ?>
            <img src="<?php echo htmlspecialchars($img['src']); ?>" alt="<?php echo htmlspecialchars($img['alt']); ?>">
            <?php endif; ?>
            <?php if ($showTitle && !empty($img['title'])): ?>
            <div class="slide-title"><?php echo htmlspecialchars($img['title']); ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($showNav && $count > 1): ?>
    <button class="nav-btn nav-prev" aria-label="Vorheriges">&#10094;</button>
    <button class="nav-btn nav-next" aria-label="Naechstes">&#10095;</button>
    <?php endif; ?>
    
    <?php if ($showDots && $count > 1): ?>
    <div class="dots">
        <?php for ($i = 0; $i < $count; $i++): ?>
        <button class="dot<?php echo $i === 0 ? ' active' : ''; ?>" data-i="<?php echo $i; ?>" aria-label="Bild <?php echo $i + 1; ?>"></button>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<script>
(function(){
    var el = document.getElementById('<?php echo $sliderId; ?>');
    if (!el) return;
    
    var slides = el.querySelectorAll('.slide'),
        dots = el.querySelectorAll('.dot'),
        prev = el.querySelector('.nav-prev'),
        next = el.querySelector('.nav-next'),
        cur = 0,
        cnt = <?php echo $count; ?>,
        intv = <?php echo $interval; ?>,
        auto = <?php echo $autoplay ? 'true' : 'false'; ?>,
        pause = <?php echo $pauseHover ? 'true' : 'false'; ?>,
        timer = null;
    
    function show(n) {
        if (n >= cnt) n = 0;
        if (n < 0) n = cnt - 1;
        cur = n;
        slides.forEach(function(s, i) { s.classList.toggle('active', i === cur); });
        dots.forEach(function(d, i) { d.classList.toggle('active', i === cur); });
    }
    
    function start() {
        if (auto && cnt > 1) {
            stop();
            timer = setInterval(function() { show(cur + 1); }, intv);
        }
    }
    
    function stop() {
        if (timer) { clearInterval(timer); timer = null; }
    }
    
    if (prev) prev.addEventListener('click', function() { show(cur - 1); start(); });
    if (next) next.addEventListener('click', function() { show(cur + 1); start(); });
    
    dots.forEach(function(d) {
        d.addEventListener('click', function() {
            show(parseInt(this.dataset.i));
            start();
        });
    });
    
    if (pause) {
        el.addEventListener('mouseenter', stop);
        el.addEventListener('mouseleave', start);
    }
    
    start();
})();
</script>
