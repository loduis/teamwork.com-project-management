<?php

return \PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'combine_consecutive_unsets' => true,
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'no_unused_imports' => true,
        'lowercase_constants' => true,
        'array_syntax' => ['syntax' => 'short'],
    ]);
