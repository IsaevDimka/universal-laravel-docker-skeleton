<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrailingCommaInMultilineArrayFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ArraySyntaxFixer::class)->call('configure', [[
            'syntax' => 'short',
    ]]);
    $services->set(UnaryOperatorSpacesFixer::class);
    $services->set(TrailingCommaInMultilineArrayFixer::class);
    $services->set(BinaryOperatorSpacesFixer::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/lib',
    ]);
    $parameters->set(Option::SETS, [
        // run and fix, one by one
         SetList::SPACES,
         SetList::ARRAY,
         SetList::DOCBLOCK,
         SetList::NAMESPACES,
         SetList::CONTROL_STRUCTURES,
         SetList::CLEAN_CODE,
         SetList::PSR_12,
    ]);
};
