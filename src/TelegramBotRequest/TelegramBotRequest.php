<?php

namespace App\TelegramBotRequest;

class TelegramBotRequest {
    public $data;
    public bool $isCallback;
    public bool $isMessage;
    public bool $isMyChatMember;
    public string $typeOfRequest;
    public string | null $text;
    public string | null $command;
    public $chatMemberStatus;

    public function __construct($request)
    {
        $this->data = $request;
        $this->isCallback = array_key_exists('callback_query', $this->data);
        $this->isMessage = array_key_exists('message', $this->data);
        $this->isMyChatMember = array_key_exists('my_chat_member', $this->data);
        $this->typeOfRequest = $this->getTypeOfRequest();
        $this->text = $this->isMessage ? $this->data['message']['text'] : null;
        $this->command = $this->isCallback ? $this->data['callback_query']['data'] : null;
        $this->chatMemberStatus = $this->isMyChatMember ? $this->data['my_chat_member']['new_chat_member']['status'] : null;
    }

    public function getTypeOfRequest()
    {
        if($this->isCallback) {
            return $this->typeOfRequest = 'callback_query';
        } elseif($this->isMessage) {
            return $this->typeOfRequest = 'message';
        } elseif($this->isMyChatMember) {
            return $this->typeOfRequest = 'my_chat_member';
        }
    }

    public function getChatID() 
    {
        return match($this->typeOfRequest) {
            'callback_query' => $this->data['callback_query']['message']['chat']['id'],
            'message' => $this->data['message']['chat']['id'],
            'my_chat_member' => $this->data['my_chat_member']['chat']['id'],
            default => null
        };
    }

}