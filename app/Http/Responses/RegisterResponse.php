<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Symfony\Component\HttpFoundation\Response;

final class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): Response
    {
        /** @var Request $request */
        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : to_route('onboarding');
    }
}
