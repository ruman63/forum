<?php
namespace App;

class Spam
{
    public function detect($body)
    {
        $this->detectInvalidKeywords($body);
        return false;
    }

    public function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'Yahoo Customer Support',
            'Fcuk'
        ];

        foreach ($invalidKeywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}
