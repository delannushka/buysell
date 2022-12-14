<?php

namespace delta;

class TicketHandler
{
    const TICKET_BUY = 'buy';
    const TICKET_SELL = 'sell';

    public static function getTypeMap(): array
    {
        return [
            self::TICKET_BUY => 'Куплю',
            self::TICKET_SELL => 'Продам'
        ];
    }

    public static function getLabel($type){
        if ($type === 'buy') {
            return 'Куплю';
        } else {
            return 'Продам';
        }
    }
}