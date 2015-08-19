<?php

namespace App\Http\Controllers\Api1;

use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Controller extends \App\Http\Controllers\Controller {

    /**
     * 参数验证器
     *
     * @param Request $request
     * @param array $rules
     * @param int $errcode
     * @param null $data
     * @param array $messages
     * @throws HttpResponseException
     */
    protected function params(Request $request, array $rules, $errcode=2002, $data=null, array $messages = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, []);

        if ($validator->fails())
        {
            throw new HttpResponseException($this->buildFailedValidationResponse(
                $request, golf_return($data, $errcode, $validator->errors()->first())
            ));
        }
    }

    /**
     * Create the response for when a request fails validation.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $errors
     * @return JsonResponse|\Illuminate\Http\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse($errors);
    }
}