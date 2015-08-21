<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Controller extends \App\Http\Controllers\Controller {

    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     *
     * @throws \Illuminate\Http\Exception\HttpResponseException
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
    }

    /**
     * ajax参数验证器
     *
     * @param Request $request
     * @param array $rules
     * @param int $status
     * @param null $data
     * @param null $forward
     * @param array $messages
     */
    protected function ajaxValidate(Request $request, array $rules, $status=0, $data=null, $forward=null, array $messages = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, []);

        if ($validator->fails())
        {
            throw new HttpResponseException($this->buildFailedValidationJsonResponse(
                ajax_return($data, $status, $validator->errors()->first(), $forward)
            ));
        }
    }

    /**
     * 创建验证失败的json响应
     *
     * @param  array $errors
     * @return JsonResponse|\Illuminate\Http\Response
     */
    protected function buildFailedValidationJsonResponse(array $errors)
    {
        return new JsonResponse($errors);
    }
}