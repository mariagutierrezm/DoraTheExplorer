<?php

namespace Flights\Library;


class FlightsClient
{
    /**
     * It makes a GET curl request to the amadeus API to request flight offers
     */
    public function searchFlightsCURL(array $data): ?array
    {
        $token = $this->getToken();
        
        $params = http_build_query($data);

        $endpoint = 'https://test.api.amadeus.com/v2/shopping/flight-offers?' . $params;

        $headers = [
            'Authorization: Bearer ' . $token['access_token'],
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resp = curl_exec($ch);
        //response code will caught http errors
        $responseCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        
        if ($responseCode != 200) {
            throw new \Exception($resp);
        }

        curl_close($ch);

        return json_decode($resp, true);
    }
    /**
     * It makes a POST curl request to the amadeus API to request the access token
     * to be use as a bearer token in future requests
     */
    public function getToken(): ?array
    {
        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => CLIENT_ID,
            'client_secret' => CLIENT_SECRET
        ];

        $endpoint = 'https://test.api.amadeus.com/v1/security/oauth2/token';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $resp = curl_exec($ch);
        //response code will caught http errors
        $responseCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }

        curl_close($ch);
        return json_decode($resp,true);
    }

}