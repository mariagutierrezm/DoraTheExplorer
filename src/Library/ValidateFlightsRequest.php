<?php

namespace Flights\Library;


class ValidateFlightsRequest
{
    /**
     * Checks the minimum required data and fields are valid and present
     */
    public function validateRequestData(array $data, bool $extraRequired = false): void
    {
        $requiredFields = [
            "originLocationCode",
            "destinationLocationCode",
            "departureDate",
            "adults",
            "max",
            "currencyCode",
        ];
        
        if($extraRequired) {
            array_push($requiredFields, "returnDate");
        }

        foreach($data as $field => $value) {
            // Checks if the filter option is in the allowed array
            if (!in_array($field, $requiredFields)) {
                throw new \Exception("Required field " . $field . " missing.");
            }
            // Checks if the value is empty
            if (empty($value)) {
                throw new \Exception("Value " . $field . " is missing.");
            }
        }
    }
}