<?php

namespace app\handlers;

class TicketHandler
{
    const TICKET_BUY = 'buy';
    const TICKET_SELL = 'sell';

    /**
     * Карта типов объявлений
     *
     * @return array
     */
    public static function getTypeMap(): array
    {
        return [
            self::TICKET_BUY => 'Куплю',
            self::TICKET_SELL => 'Продам'
        ];
    }

    /**
     * Метод полуения типа объвления на русском языке
     *
     * @param string $type тип объявления
     * @return string
     */
    public static function getLabel(string $type): string
    {
        if ($type === self::TICKET_BUY) {
            return 'Куплю';
        } else {
            return 'Продам';
        }
    }
}