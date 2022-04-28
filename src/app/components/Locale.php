<?php

declare(strict_types=1);

namespace App\Components;

use Phalcon\Di\Injectable;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;



final class Locale extends Injectable
{
    public function getTranslator(): NativeArray
    {
        // Ask browser what is the best language
        $language = $this->request->getBestLanguage();
        $messages = [];

        $translationFile = '../app/messages/' . $language . '.php';

        if (file_exists($translationFile) !== true) {
            $translationFile = '../app/messages/en.php';
        }

        require $translationFile;

        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);

        return $factory->newInstance(
            'array',
            [
                'content' => $messages,
            ]
        );
    }
}
