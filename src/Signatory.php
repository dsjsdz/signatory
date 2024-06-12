<?php

namespace Dsjsdz\Signatory;


class Signatory
{

    private string $APP_KEY = '';

    /**
     * @param string $APP_KEY
     * @throws Error
     */
    public function __construct(string $APP_KEY)
    {
        if ($APP_KEY === '') {
            throw new Error("app key can not be empty");
        }

        $this->APP_KEY = $APP_KEY;
    }

    /**
     * @throws Error
     */
    public function genSignature(array $args): string
    {
        unset($args["sign"]);
        $keys = [];
        $code = "";

        if (!array_key_exists("timestamp", $args)) {
            throw new Error("timestamp can not be empty");
        }

        foreach ($args as $key => $value) {
            $temp = sprintf("%s", $value);
            if ($temp === "" || $temp === "NULL") {
                continue;
            }


            $keys = array_merge($keys, [$key]);
        }
        sort($keys);

        foreach ($keys as $key) {
            $code .= sprintf("%s=%s&", $key, $args[$key]);
        }

        $code = sprintf("%skey=%s", $code, $this->APP_KEY);

        return strtoupper(md5($code));
    }

    /**
     * @throws Error
     */
    public function toBase64String(array $args): string
    {
        if (!array_key_exists("timestamp", $args)) {
            $args["timestamp"] = sprintf("%d", time());
        }

        foreach ($args as $key => $value) {
            if ($value == null) {
                unset($args[$key]);
                continue;
            }

            $temp = sprintf("%s", $value);
            if ($temp === "" || $temp === "NULL") {
                unset($args[$key]);
            }
        }

        if (!array_key_exists("sign", $args)) {
            $args["sign"] = $this->genSignature($args);
        }

        return base64_encode(json_encode($args));
    }

    /**
     * @throws Error
     */
    public function decryptBase64String(string $args): array
    {
        if (strlen($args) === 0) {
            throw new Error("base64 string can not be empty");
        }

        $payload = base64_decode($args, true);
        if ($payload) {
            throw new Error("base64 string decode failed");
        }

        return json_decode($payload, true);
    }

    /**
     * @description: 校验签名
     * @param array $args
     * @param string $sign
     * @return bool
     * @throws Error
     */
    public function checkSignature(array $args, string $sign): bool
    {
        return $this->genSignature($args) === $sign;
    }
}