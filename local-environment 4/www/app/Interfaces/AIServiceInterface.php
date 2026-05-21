<?php

namespace App\Interfaces;

interface AIServiceInterface
{
    public function improve(string $text): string;
}