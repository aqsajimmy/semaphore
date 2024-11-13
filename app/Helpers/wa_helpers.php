<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('sendMessage')) {
    /**
     * Send a message using the specified API.
     *
     * @param string $number - Receiver's phone number with country code.
     * @param string $text - Text message to send.
     * @param string|null $file - URL of the file (optional, if sending image, video, or document).
     * @param string $apiKey - API key for authentication.
     * @param string $type - Message type ('text', 'image', 'video', 'document').
     * @param string|null $filename - Optional custom filename if sending a document.
     *
     * @return array - Status of the message send attempt.
     */
    function sendMessage($apiKey, $type, $number, $text, $file, $filename)
    {
        // Build the payload for the API request
        $data = [
            'apikey' => $apiKey,
            'mtype' => $type,
            'receiver' => $number,
            'text' => $text,
            'url' => $file,
            'filename' => $filename,
        ];

        // Send request to the external API
        $response = Http::post('https://api.jimx.dev/api/send-message', $data);

        // Return success or failure response
        if ($response->successful()) {
            return ['status' => 'true', 'message' => 'Message sent successfully!'];
        } else {
            return ['status' => 'false', 'message' => 'Failed to send message.'];
        }
    }
}
