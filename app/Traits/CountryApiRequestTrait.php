<?php

namespace App\Traits;

use Exception;

trait CountryApiRequestTrait
{
    /**
     * Perform an API request using cURL.
     *
     * @param string $url
     * @return mixed
     */
    public function fetchApiData($url)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
                ),
            ));
            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception('Error occurred: ' . curl_error($curl));
            }

            curl_close($curl);

            // Parse the response
            $data = json_decode($response);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response');
            }

            return $data;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
