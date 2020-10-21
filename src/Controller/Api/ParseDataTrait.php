<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

trait ParseDataTrait
{
    /**
     * @return array<string, string>
     */
    private function parseData(Request $request): array
    {
        $data = $request->query->get('data');
        if (null === $data || '' === $data) {
            throw new BadRequestException('No data param');
        }

        $list = explode(';', $data);
        $params = [];
        foreach ($list as $item) {
            $keyValue = explode(':', $item);
            if (count($keyValue) !== 2) {
                throw new BadRequestException('Bad data param format: `' . $item . '`');
            }
            $params[$keyValue[0]] = $keyValue[1];
        }

        return $params;
    }

    /**
     * @return string[]
     */
    private function getTerminalCodeSymbol(Request $request): array
    {
        $data = $this->parseData($request);

        if (null === $code = $data['terminal_code'] ?? null) {
            throw new BadRequestException('No terminal_code in data');
        }
        if (null === $ticker = $data['ticker_symbol'] ?? null) {
            throw new BadRequestException('No ticker_symbol in data');
        }

        return [$code, $ticker];
    }
}
