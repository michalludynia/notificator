<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/../src');

return (new PhpCsFixer\Config())->setRules([
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
])
    ->setFinder($finder);
