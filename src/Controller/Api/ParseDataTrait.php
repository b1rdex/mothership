<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

trait ParseDataTrait
{
    /**
     * @return string[]
     * @phpstan-return array<string, string>
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
}
