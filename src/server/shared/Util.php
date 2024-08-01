<?php
namespace MW\Shared;

class Util
{

    public static function HandlePOST(): array
    {
        $postBody = file_get_contents('php://input');
        $data = $postBody === false ? [] : \json_decode($postBody, true);

        if (empty($data) || !isset($data['resource'])) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_POST_PARAMETERS,
                logData: [\json_encode($data, JSON_PRETTY_PRINT)],
            );
        }

        $resource = isset($data['resource']) ? $data['resource'] : '';
        $payload = isset($data['payload']) ? $data['payload'] : [];
        $query = $_GET;
        return [$resource, $payload, $query];
    }

    public static function CompareStructures($test, $template, $options = [])
    {
        // checkMissed === true => если тестируемый элемент отсутствует, то это ошибка
        // checkNull === false => если тестируемый элемент равен NULL или отсутствует, то не важно каким должен быть тип. Это НЕ ошибка.

        if (!isset($options['checkMissed'])) {
            $options['checkMissed'] = true;
        }

        if (!isset($options['checkNull'])) {
            $options['checkNull'] = true;
        }

        //  echo "-----------------------\n";
        //  var_dump($test);
        //  var_dump($template);
        //  echo "-----------------------\n";
        if (gettype($template) === 'array') {

            if (gettype($test) !== 'array') {
                return false;
            }

            if ($template['_type'] === 'object') {

                $template_keys = array_keys($template);
                $test_keys = array_keys($test);

                if ($options['checkMissed'] && count($template_keys) - 1 !== count($test_keys)) {
                    return false;
                }

                foreach ($template_keys as $key) {
                    if ($key === '_type') {
                        continue;
                    }

                    // no such key
                    if ($options['checkNull'] && !isset($test[$key])) {
                        return false;
                    }

                    if (!isset($template[$key])) {
                        return false;
                    }

                    if (Util::CompareStructures($test[$key], $template[$key], $options) === false) {
                        return false;
                    }

                }

                return true;
            } else if ($template['_type'] === 'array') {
                $keyType = $template['_keyType'];
                $itemTemplate = $template['_itemTemplate'];
                foreach ($test as $key => $item) {
                    if (gettype($key) !== gettype($keyType)) {
                        return false;
                    }

                    if (compare_structures($item, $itemTemplate, $options) === false) {
                        return false;
                    }

                }

                return true;
            }

            return false;
        } else {
            if (gettype($test) === 'NULL' && !$options['checkNull']) {
                return true;
            }

            return gettype($template) === gettype($test);
        }
    }

    public static function MakeSuccessOperationResult($data = [], $test = [])
    {
        return new OperationResult(OperationResult::UC_SUCCESS, $data, $test);
    }

    public static function MakeFailOperationResult($data = [], $test = [])
    {
        return new OperationResult(OperationResult::UC_FAIL, $data, $test);
    }

    public static function MakeErrorOperationResult($errCode, $args = [], $test = [])
    {
        return new OperationResult(OperationResult::ERROR, [
            'code' => $errCode,
            'args' => $args,
            'test' => $test,
        ]);
    }

    public static function CalcExecutionTime($startTime)
    {
        $finishTime = microtime(true);
        return [
            'start' => date('Y-m-d H:i:s', $startTime),
            'finish' => date('Y-m-d H:i:s', $finishTime),
            'execution' => sprintf('%f', $finishTime - $startTime),
        ];
    }

    public static function RenderTemplate($template)
    {
        ob_start();
        try {
            require "{$template}";
            // $res = ob_get_contents();
            // ob_end_clean();
        } catch (\Throwable $ex) {
            ob_end_clean();
            throw $ex;
        }
        $res = ob_get_clean();
        return $res;
    }

    public static function GenerateToken($key = '')
    {
        return md5(uniqid($key, true));
    }

    public static function GenerateDigitalCode($length = 4)
    {
        return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    public static function GenerateHash($value, $salt)
    {
        return crypt($value, $salt);
    }

    public static function EncryptPassword($login, $password)
    {
        $salt = Util::GenerateToken($login . rand());
        return [$salt, crypt($password, $salt)];
    }

    public static function Fetch($method, $url, $settings = [])
    {
        // defaults
        $settings['body'] = isset($settings['body']) ? $settings['body'] : '';

        $request = [];
        $request['method'] = $method;
        $request['body'] = $settings['body'];

        $ch = curl_init();

        if (isset($settings['query'])) {
            $url = $url . '?' . $settings['query'];
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $request['url'] = $url;

        if ($method === 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $settings['body']);
        }

        if ($method === 'put') {
            curl_setopt($ch, CURLOPT_PUT, 1);
        }

        if ($method === 'delete') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        if (isset($settings['ipResolve'])) {
            if ($settings['ipResolve'] === 'ipv4') {
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            }

            if ($settings['ipResolve'] === 'ipv6') {
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
            }
        }

        if (isset($settings['headers'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $settings['headers']);
            $request['header'] = $settings['headers'];
        }

        if (isset($settings['userpwd'])) {
            curl_setopt($ch, CURLOPT_USERPWD, $settings['userpwd']);
        }

        if (isset($settings['upload'])) {
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch, CURLOPT_INFILESIZE, $settings['upload']['size']);
            curl_setopt($ch, CURLOPT_INFILE, $settings['upload']['file']);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $file = null;
        if (isset($settings['file'])) {
            $file = @fopen($settings['file'], 'w');
            curl_setopt($ch, CURLOPT_FILE, $file);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_VERBOSE, false);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $status = curl_getinfo($ch);

        curl_close($ch);

        if (isset($settings['file'])) {
            fclose($file);
        }

        return [$response, (empty($error) ? false : $error), $status, $request];
    }
}
