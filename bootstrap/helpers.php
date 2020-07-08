<?php
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}


/**
 * @param int $code 返回码
 * @param string $message 返回说明
 * @param array $data 成功时返回数据
 * @param array $errors 失败时返回数据
 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
 */
function responseApi(int $code, string $message, array $data = [], array $errors = [])
{
    return response(compact('code', 'message', 'data', 'errors'));
}

/**
 * @param array $data 返回数据
 * @param string $message 说明
 * @param int $code 返回码
 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
 */
function success(array $data = [], string $message = 'OK', int $code = 0)
{
    $code = $code == 0 ? $code : -1;

    return responseApi($code, $message, $data, []);
}

/**
 * @param string $message 说明
 * @param int $code 返回码
 * @param array $errors 失败时返回数据
 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
 */
function failed(string $message = 'Failed', int $code = -1, array $errors = [])
{
    $code = $code == 0 ? -1 : $code;

    return responseApi($code, $message, [], $errors);
}
