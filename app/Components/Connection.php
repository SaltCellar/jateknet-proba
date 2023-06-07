<?php

namespace App\Components;

class Connection
{
    use \App\Traits\PrivateSingleton;

    public const METHOD_GET = "GET";
    // POST, PUT, DELETE (Most csak GET-elünk)

    private const ROOT_PATH = "https://api.test/";

    public static function send(string $method, string $path): ?array
    {
        if (TEST_CONNECTION) {
            return self::getInstance()->execute_send_test($method, $path);
        } else {
            return self::getInstance()->execute_send($method, $path);
        }
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /* Curl */
    /* -------------------------------------------------------------------------------------------------------------- */

    private function execute_send(string $METHOD, string $URL): ?array
    {
        if (!in_array($METHOD, [
            self::METHOD_GET
            // Elfogadható metódusok request küldéshez...
        ])) {
            throw new \RuntimeException("Invalid request method selected: " . $METHOD);
        }

        // Auth -ot és Root URL -t hozzá rakni
        $URL = $this->buildUrl($URL);

        // Hagyományos lib nélküli CURL
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $METHOD);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json' ]);

        $result = curl_exec($ch);
        curl_close($ch);

        // Loggoloás
        // TODO: Hát.. ez ilyen gyors logolásos megoldás (remélem a próbához elég lesz)
        $this->log($METHOD, $URL, (is_string($result) ? $result : null));

        // TODO: Itt time-out, sturcture és egyéb validációt kellene még...

        return json_decode($result, true);
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /* Curl Test (Response Test) */
    /* -------------------------------------------------------------------------------------------------------------- */

    private function execute_send_test(string $METHOD, string $URL): ?array
    {
        if (!in_array($METHOD, [self::METHOD_GET])) {
            throw new \RuntimeException("Invalid request method selected: " . $METHOD);
        }

        $result = [];
        if (str_starts_with($URL, "products")) {
            $result = [
                "result" => [
                    "name" => "Product Name...",
                    "desc" => "Product Desc...",
                    "price" => 1400,
                ],
            ];
        } elseif (str_starts_with($URL, "categories")) {
            $result = [
                "result" => [
                    "name" => "Category Name...",
                    "desc" => "Category Desc..."
                ],
            ];
        } else {
            $result = [
                "error" => "Invalid path!",
            ];
        }

        $result = json_encode($result, JSON_UNESCAPED_UNICODE);

        $URL = $this->buildUrl($URL);
        $this->log($METHOD, $URL, (is_string($result) ? $result : null));
        return json_decode($result, true);
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /* URL & Auth */
    /* -------------------------------------------------------------------------------------------------------------- */

    private function buildUrl(string $path): string
    {
        return self::ROOT_PATH . $path . "/?auth=" . $this->generateAuth();
    }


    private function generateAuth(): string
    {
        $date_stamp = strtotime(date('Y-m-d') . " " . "00:00:00");
        return md5(REMOTE_TOKEN . $date_stamp);
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /* Log */
    /* -------------------------------------------------------------------------------------------------------------- */

    private const LOG_ALLOWED = true;
    private const LOG_CONTENT_NEW_LINE = "\n\r";
    private const PATH_LOG_DIR = PATH_STORAGE . DIRECTORY_SEPARATOR . "connection_logs";

    private function log(string $method, string $path, ?string $response): void
    {
        if (!self::LOG_ALLOWED)
        {
            return;
        }

        $date_stamp = strtotime(date('Y-m-d') . " " . "00:00:00");
        $log_file = self::PATH_LOG_DIR . DIRECTORY_SEPARATOR . $date_stamp . ".log";

        $response = is_null($response) ? ( "[CONNECTION ERROR]" ) : $response;

        $content =
            "[".time()."][".date('Y-m-d h:i:s')."]" . self::LOG_CONTENT_NEW_LINE.
            $method . " " . $path . self::LOG_CONTENT_NEW_LINE.
            $response . self::LOG_CONTENT_NEW_LINE;

        file_put_contents($log_file, $content, FILE_APPEND);
    }

}
