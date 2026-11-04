<?php

namespace App\Translation;

use App\Translation\Concerns\MergesSourceLanguageTranslations;
use JoeDixon\Translation\Drivers\File;

class FileTranslationDriver extends File
{
    use MergesSourceLanguageTranslations;
}
