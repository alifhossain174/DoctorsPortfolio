<?php

// Fixed layout order is code-owned. Only optional preview/CTA sections can be hidden.
return [
    'home.hero' => ['position' => 0, 'optional' => false],
    'home.care' => ['position' => 1, 'optional' => true],
    'home.doctor' => ['position' => 2, 'optional' => true],
    'home.journal' => ['position' => 3, 'optional' => true],
    'home.gallery' => ['position' => 4, 'optional' => true],
    'home.cta' => ['position' => 5, 'optional' => true],
    'about.profile' => ['position' => 0, 'optional' => false],
    'about.information' => ['position' => 1, 'optional' => false],
    'about.cta' => ['position' => 2, 'optional' => true],
    'services.services' => ['position' => 0, 'optional' => false],
    'services.cta' => ['position' => 1, 'optional' => true],
    'diagnostics.laboratory' => ['position' => 0, 'optional' => false],
    'diagnostics.cta' => ['position' => 1, 'optional' => true],
    'blog.posts' => ['position' => 0, 'optional' => false],
    'blog.cta' => ['position' => 1, 'optional' => true],
    'gallery.images' => ['position' => 0, 'optional' => false],
    'gallery.cta' => ['position' => 1, 'optional' => true],
    'contact.information_form' => ['position' => 0, 'optional' => false],
    'article.article' => ['position' => 0, 'optional' => false],
    'article.cta' => ['position' => 1, 'optional' => true],
];
