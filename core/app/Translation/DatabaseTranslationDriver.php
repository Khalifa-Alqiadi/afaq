<?php

namespace App\Translation;

use App\Translation\Concerns\MergesSourceLanguageTranslations;
use JoeDixon\Translation\Drivers\Database;

class DatabaseTranslationDriver extends Database
{
    use MergesSourceLanguageTranslations;
}
