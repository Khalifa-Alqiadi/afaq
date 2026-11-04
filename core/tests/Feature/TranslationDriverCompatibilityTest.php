<?php

use App\Translation\FileTranslationDriver;
use JoeDixon\Translation\Drivers\Translation;

test('translation admin uses the PHP compatible application driver', function () {
    expect(app(Translation::class))->toBeInstanceOf(FileTranslationDriver::class);
});

test('translation admin merges source keys without pass by reference warnings', function () {
    set_error_handler(function (int $severity, string $message, string $file, int $line): never {
        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    try {
        $translations = app(Translation::class)->getSourceLanguageTranslationsWith('en');
    } finally {
        restore_error_handler();
    }

    expect($translations)->not->toBeEmpty();
});
