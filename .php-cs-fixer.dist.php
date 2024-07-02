<?php
/**
 * @author Alexander Tebiev - https://github.com/beeyev
 */
$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules([
        '@PhpCsFixer' => true,
        '@PER-CS2.0' => true,
        '@PER-CS2.0:risky' => true,
        // Write conditions in Yoda style (`true`), non-Yoda style (`['equal' => false, 'identical' => false, 'less_and_greater' => false]`) or ignore those conditions (`null`) based on configuration.
        'yoda_style' => false,
        // Visibility MUST be declared on all properties and methods; `abstract` and `final` MUST be declared before the visibility; `static` MUST be declared after the visibility.
        'visibility_required' => ['elements' => ['method', 'property']],
        // Concatenation should be spaced according configuration.
        'concat_space' => ['spacing' => 'one'],
        // Sorts PHPDoc types.
        'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
        // Single-line comments and multi-line comments with only one line of actual content should use the `//` syntax.
        'single_line_comment_style' => false,
        // Add leading `\` before function invocation to speed up resolving.
        'native_function_invocation' => false,
        // PHPDoc summary should end in either a full stop, exclamation mark, or question mark.
        'phpdoc_summary' => false,
        // `@return void` and `@return null` annotations should be omitted from PHPDoc.
        'phpdoc_no_empty_return' => false,
        // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
        'multiline_whitespace_before_semicolons' => false,
        // Replace control structure alternative syntax to use braces.
        'no_alternative_syntax' => false,
        // Comments must be surrounded by spaces.
        PhpCsFixerCustomFixers\Fixer\CommentSurroundedBySpacesFixer::name() => true,
        // Constructor's empty braces must be single line.
        PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer::name() => true,
        // There can be no imports from the global namespace.
        PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer::name() => true,
        // Trailing comma in the list on the same line as the end of the block must be removed.
        PhpCsFixerCustomFixers\Fixer\NoTrailingCommaInSinglelineFixer::name() => true,
        // There must be no useless parentheses.
        PhpCsFixerCustomFixers\Fixer\NoUselessParenthesisFixer::name() => true,
        // The strlen or mb_strlen functions should not be compared against 0.
        PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer::name() => true,
        // There must be no superfluous parameters in PHPDoc.
        PhpCsFixerCustomFixers\Fixer\PhpdocNoSuperfluousParamFixer::name() => true,
        // The @var annotations must be on a single line if they are the only content.
        PhpCsFixerCustomFixers\Fixer\PhpdocSingleLineVarFixer::name() => true,
        // PHPDoc types must be trimmed.
        PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer::name() => true,
        // Statements not preceded by a line break must be preceded by a single space.
        PhpCsFixerCustomFixers\Fixer\SingleSpaceBeforeStatementFixer::name() => true,
        // In PHPDoc, the class or interface element self should be preferred over the class name itself.
        PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer::name() => true,
        // Calls to `PHPUnit\Framework\TestCase` static methods must all be of the same type, either `$this->`, `self::` or `static::`.
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
        // Removes the leading part of fully qualified symbol references if a given symbol is imported or belongs to the current namespace.
        'fully_qualified_strict_types' => false,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_strict' => false,
        'string_implicit_backslashes' => false,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant',
                'property',
                'construct',
                'method:__invoke',
                'public',
                'protected',
                'private',
                'magic',
            ],
            'sort_algorithm' => 'none',
            'case_sensitive' => false,
        ],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->ignoreUnreadableDirs()
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
            ->append([
                __DIR__ . '/.php-cs-fixer.dist.php',
            ]),
    );
