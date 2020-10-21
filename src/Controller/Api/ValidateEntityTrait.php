<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidateEntityTrait
{
    private function validateEntity(ValidatorInterface $validator, object $entity): ?Response
    {
        if (0 === count($violations = $validator->validate($entity))) {
            return null;
        }

        $content = '<h3>Validation error</h3><ul>';
        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $content .= '<li>'.$violation->getPropertyPath().': '.$violation->getMessage().'</li>';
        }
        $content .= '</ul>';

        return new Response($content, 400, ['content-type' => 'text/html']);
    }
}
