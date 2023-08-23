<?php
/**
 * 丝瓜管理后台（Sigua admin）
 * 一个基于 Laravel 的管理后台系统，让中后台开发更简单！
 *
 * @author    Yiba <yibafun@gmail.com>
 * @copyright sigua.tech
 * @license   MIT (http://opensource.org/licenses/MIT)
 */
declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
    ];

    public function handle($request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            // token 错误则重新下发 token
            return $this->responseAndResendToken($request, $e->getMessage());
        }
    }

    protected function getTokenFromRequest($request): ?string
    {
        if ($xsrfToken = $request->input('_xsrf_token')) {
            try {
                return CookieValuePrefix::remove($this->encrypter->decrypt($xsrfToken, static::serialized()));
            } catch (DecryptException) {
            }
        }

        return parent::getTokenFromRequest($request);
    }

    protected function responseAndResendToken($request, $message): JsonResponse
    {
        $cookie = $this->newCookie($request, config('session'));
        return response()
            ->json(['code' => 419, 'message' => $message, 'token_cookie_sent' => 1], 419)
            ->withCookie($cookie);
    }
}
