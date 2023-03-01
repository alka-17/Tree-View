<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\MagicConstants;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
class Handler extends ExceptionHandler
{
    use MagicConstants;
    protected $message = 'message';
    protected $api = 'api/*';

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
 /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is($this->api)) {
            return $this->handleApiException($request, $exception);
        } else {
            return parent::render($request, $exception);
        }
    }

    private function handleApiException($request, $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }
        return $this->customApiResponse($exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        return $request->is($this->api)
            ? $this->invalidJson($request, $e)
            : $this->invalid($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->is($this->api)
            ? response()->json(['message' => $exception->getMessage()], 401)
            : 'Unauthorized access.';
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = FIVEHUNDRED;
        }
        $response = [];
        switch ($statusCode) {
            case UNAUTHORIZED:
                $response[$this->message] = 'Unauthorized access.';
                break;

            case FORBIDDEN:
                $response[$this->message] = trans('message.ERROR_MESSAGES.FORBIDDEN_ERROR');
                break;

            case NOT_FOUND:
                $response[$this->message] = trans('message.ERROR_MESSAGES.NOT_FOUND_ERROR');
                break;

            case METHOD_NOT_FOUND:
                $response[$this->message] = $exception->getMessage();
                break;

            case UNPROCESSABLE:
                $response[$this->message] = $exception->original[$this->message];
                $response['errors'] = $exception->original['errors'];
                break;
            default:
                $response[$this->message] = ($statusCode == FIVEHUNDRED) ? $exception->getMessage() : trans('message.ERROR_MESSAGES.SOMETHING_WRONG');
                break;
        }
        $response['status'] = $statusCode;

        return response()->json($response, $statusCode);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
