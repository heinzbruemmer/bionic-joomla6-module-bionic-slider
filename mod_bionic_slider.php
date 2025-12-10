<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Uri\Uri;

$images = [];
for ($i = 1; $i <= 5; $i++) {
    $path = $params->get("image{$i}", '');
    if (!empty($path)) {
        $images[] = [
            'src'   => Uri::root() . $path,
            'link'  => $params->get("image{$i}_link", ''),
            'alt'   => $params->get("image{$i}_alt", ''),
            'title' => $params->get("image{$i}_title", ''),
        ];
    }
}

if (empty($images)) {
    return;
}

$sliderId = 'mod-slider-' . $module->id;

require ModuleHelper::getLayoutPath('mod_bionic_slider', $params->get('layout', 'default'));
