<?php

namespace App\Buttons;

final class ButtonService {

    private function __construct()
    {

    }

    public static function getInlineKeyboardForStart(): string
    {
        return json_encode([
                'inline_keyboard' => [
                    [
                        Button::create('Получить случайный gif', '/gif')->toArray(),
                    ],
                    [
                        Button::create('Прогноз погоды', '/wheather')->toArray(),
                    ],
                ]
            ]);
    }

    public static function getInlineKeyboardForGif(): string
    {
        return json_encode([
                'inline_keyboard' => [
                    [
                        Button::create('Назад', '/start')->toArray(),
                    ]
                ]
            ]);
    }

    public static function getInlineKeyboardForWheather(): string
    {
        return json_encode([
                'inline_keyboard' => [
                    [
                        Button::create('Назад', '/start')->toArray(),
                    ],
                ]
            ]);
    }
}