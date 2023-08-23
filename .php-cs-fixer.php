#!/usr/bin/env php
<?php
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<'EOF'
丝瓜管理后台（Sigua admin）
一个基于 Laravel 的管理后台系统，让中后台开发更简单！

@author    Yiba <yibafun@gmail.com>
@copyright sigua.tech
@license   MIT (http://opensource.org/licenses/MIT)
EOF;

$rules = [
    // '@PHP80Migration' => true,
    '@PSR12' => true,
    '@Symfony' => true,
    '@DoctrineAnnotation' => true,
    '@PhpCsFixer' => true,
    'header_comment' => [
        'comment_type' => 'PHPDoc',
        'header' => $header,
        'separate' => 'none',
        'location' => 'after_open',
    ],
    'array_syntax' => [
        'syntax' => 'short',
    ],
    'list_syntax' => [
        'syntax' => 'short',
    ],
    'concat_space' => [
        'spacing' => 'one',
    ],
    'blank_line_before_statement' => [
        'statements' => [
            'declare',
        ],
    ],
    'general_phpdoc_annotation_remove' => [
        'annotations' => [
            'author',
        ],
    ],
    'ordered_imports' => [
        'imports_order' => [
            'class', 'function', 'const',
        ],
        'sort_algorithm' => 'alpha',
    ],
    'single_line_comment_style' => [
        'comment_types' => [
        ],
    ],
    'yoda_style' => [
        'always_move_variable' => false,
        'equal' => false,
        'identical' => false,
    ],
    'phpdoc_align' => [
        'align' => 'left',
    ],
    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'no_multi_line',
    ],
    'constant_case' => [
        'case' => 'lower',
    ],
    'class_attributes_separation' => true,
    'combine_consecutive_unsets' => true,
    'declare_strict_types' => true,
    'linebreak_after_opening_tag' => false,
    'lowercase_static_reference' => true,
    'no_useless_else' => true,
    'no_unused_imports' => true,
    'not_operator_with_successor_space' => true,
    'not_operator_with_space' => false,
    'ordered_class_elements' => true,
    'php_unit_strict' => false,
    'phpdoc_separation' => false,
    'single_quote' => true,
    'standardize_not_equals' => true,
    'multiline_comment_opening_closing' => true,
];

$finder = Finder::create()
    ->in([
         __DIR__ . '/app',
        // __DIR__ . '/config',
        __DIR__ . '/database',
        // __DIR__ . '/routes',
        __DIR__ . '/tests',
        __DIR__ . '/sigua',
    ])
    ->exclude(__DIR__ . '/vendor')
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(false);
