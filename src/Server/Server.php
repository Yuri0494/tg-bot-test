<?php

namespace App\Server;

use Exception;
use GuzzleHttp\Client;
use App\TelegramBot\TelegramBot;
use App\TelegramBotRequest\TelegramBotRequest;
use App\Database\FileSystemBotDb;
use App\Buttons\ButtonService;
use App\Api\WheatherApiAdapters\WheatherApiFree;
use App\Api\WheatherApiAdapters\Wheather;
use App\Api\TelegramApi\TelegramApi;
use App\HttpApiAdapters\GuzzleHttpAdapter;

class Server {
    private $tgBot;
    private $request;
    private $chatID;
    
    public function __construct() 
    {
        $this->tgBot = new TelegramBot(
            new FileSystemBotDb(__DIR__ . '/db2.json'), 
            new TelegramApi((new GuzzleHttpAdapter('https://api.telegram.org/bot6768896921:AAHSiWv6mmLSdd6b7kLVOIy9XXKltN8KIlg/')))
        );
        $this->request = new TelegramBotRequest(json_decode(file_get_contents('php://input'), true));
        $this->chatID = (string) $this->request->getChatID();
    }

    public function handleRequest()
    {
        $isNewMember = !$this->tgBot->db->chatIdExists($this->chatID);

        if($isNewMember) {
            $this->actionNewMember();
        }

        if($this->request->isMessage) {
            $lastCommand = $this->tgBot->db->getLastCommandByChatId($this->chatID);
            if($lastCommand === '/wheather') {
                $this->actionGetWheatherInfo();
            }
        }
    
        if($this->request->isCallback) {

            if($this->request->command === '/start') {
                $this->actionStart();
            }
        
            if($this->request->command === '/gif') {
                $this->actionGif();
            }

            if($this->request->command === '/wheather') {
                $this->actionWheather();
            }
        }

        if($this->request->isMyChatMember) {
            if($this->request->chatMemberStatus === 'kicked') {
                $this->tgBot->db->unsetChatId($this->chatID);
            }
        }
    }

    private function actionNewMember()
    {
        try {
            $this->tgBot->db->setChatId($this->chatID);
            $this->tgBot->api->sendMessage($this->chatID, 'Что будем делать?', ['reply_markup' => ButtonService::getInlineKeyboardForStart()]);
        } catch (Exception $e) {
            $this->tgBot->api->sendMessage($this->chatID, 'Ой, что-то пошло не так', ['reply_markup' => ButtonService::getInlineKeyboardForStart()]);
        }
    }

    private function actionStart()
    {
        try {
            $this->tgBot->db->setLastCommandByChatId($this->chatID, '/start');
            $this->tgBot->api->sendMessage($this->chatID, 'Что будем делать?', ['reply_markup' => ButtonService::getInlineKeyboardForStart()]);
        } catch (Exception $e) {
            $this->tgBot->api->sendMessage($this->chatID, 'Ой, что-то пошло не так', ['reply_markup' => ButtonService::getInlineKeyboardForStart()]);
        }
    }

    private function actionGif()
    {
        try {
            $this->tgBot->db->setLastCommandByChatId($this->chatID, '/gif');
            $this->tgBot->api->sendMessage($this->chatID, 'Получите случайную гифку!', ['reply_markup' => ButtonService::getInlineKeyboardForGif()]);
        } catch (Exception $e) {
            $this->tgBot->api->sendMessage($this->chatID, 'Ой, что-то пошло не так', ['reply_markup' => ButtonService::getInlineKeyboardForStart()]);
        }
    }

    private function actionWheather()
    {
        try {
            $this->tgBot->db->setLastCommandByChatId($this->chatID, '/wheather');
            $this->tgBot->api->sendMessage($this->chatID, 'Погода в каком городе вас интересует (отправьте название города в сообщении)', ['reply_markup' => ButtonService::getInlineKeyboardForGif()]);
        } catch (Exception $e) {
            $this->tgBot->api->sendMessage($this->chatID, 'Ой, что-то пошло не так', ['reply_markup' => ButtonService::getInlineKeyboardForStart()]);
        }
    }

    private function actionGetWheatherInfo()
    {
        try {
            $wheather = Wheather::createByWAFData((new WheatherApiFree())->getWheatherInfo($this->request->text));
            $this->tgBot->api->sendMessage(
                $this->chatID, 
                $wheather->toMessage($this->request->text), 
                ['reply_markup' => ButtonService::getInlineKeyboardForWheather()]
            );
        } catch (Exception $e) {
            $this->tgBot->api->sendMessage(
                $this->chatID, 
                'Не удалось узнать прогноз погоды для ' . $this->request->text . '. Причина: ' . $e, 
                ['reply_markup' => ButtonService::getInlineKeyboardForWheather()]
            );
        }
    }
}