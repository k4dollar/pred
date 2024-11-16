<?php
// Set the access token and Telegram API endpoint
$token = '7864439922:AAF7ffzgJvgHu69zqe9s2jXY150IEwGoFVQ';
$api_endpoint = 'https://api.telegram.org/bot' . $token;

// Set the default time zone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');

// Define the array
$colors_and_sizes = array('GREEN', 'SMALL', 'BIG', 'RED');

// Mapping videos to values
$video_paths = array(
    'RED' => '/home/flashank/public_html/flashokwin/red22.MP4',
    'GREEN' => '/home/flashank/public_html/flashokwin/green22.MP4',
    'BIG' => '/home/flashank/public_html/flashokwin/big22.MP4',
    'SMALL' => '/home/flashank/public_html/flashokwin/small22.MP4'
);

// Function to get the first issue number from the API
function getFirstIssueNumber() {
    $apiUrl = 'https://imgametransit.com/api/webapi/GetNoaverageEmerdList';
    
    // Prepare the payload
    $payload = json_encode([
        "pageSize" => 10,
        "pageNo" => 1,
        "typeId" => 1,
        "language" => 0,
        "random" => "38f5d1ecb8244e91a60a6f150a5fc806",
        "signature" => "D35A6E07E0FD024DF7C94757B27D0F8E",
        "timestamp" => time()
    ]);
    
    // Initialize cURL session
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response
    $data = json_decode($response, true);

    // Check if data is valid and return the first issue number
    if (isset($data['data']['list'][0]['issueNumber'])) {
        return $data['data']['list'][0]['issueNumber'];
    }
    return 'N/A';
}

// Fetch the first issue number
$first_issue_number = getFirstIssueNumber();

// Set the chat_ids of your channels here
$channel_chat_id1 = '-1002123531024'; // Your first channel's chat_id

// Check for the 'last_message_timestamp' file
if (file_exists('last_message_timestamp')) {
    // Read the last message timestamp from the file
    $last_message_timestamp = file_get_contents('last_message_timestamp');

    // Check if 60 seconds have passed since the last message was sent
    if (time() - $last_message_timestamp >= 50) {
        // Get a random key from the array
        $random_key = array_rand($colors_and_sizes);

        // Get the random value using the random key
        $random_string = $colors_and_sizes[$random_key];

        // Get the path to the video file based on the random string value
        $video_path = isset($video_paths[$random_string]) ? $video_paths[$random_string] : '/default/video/path.mp4';
        
        $incremented_issue_number = $first_issue_number + 1;

        // Compose the message with the fetched issue number
        $message = "<u>MAINTAIN UPTO LEVEL 7</u> \n\n";
        $message .= "<b>Period no:</b> <code>$incremented_issue_number</code> \n\n";
        $message .= "<code>ðŸŽ¯ $random_string</code> \n";

        // Construct the vertical inline keyboard
        $inline_keyboard = json_encode([
            'inline_keyboard' => [
                [['text' => 'CHANNEL LINK', 'url' => 'https://t.me/FLASH_CLAN']],
                [['text' => 'OK WIN GAME LINK', 'url' => 'https://www.okwin0.in//#/register?invitationCode=866225840164']]
            ]
        ]);

        // Prepare the Telegram message payload
        $post_fields1 = array(
            'chat_id' => $channel_chat_id1,
            'caption' => $message,
            'video' => new CURLFile(realpath($video_path)),
            'parse_mode' => 'HTML',
            'reply_markup' => $inline_keyboard
        );

        // Send the video to Telegram
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $api_endpoint . '/sendvideo');
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_fields1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $result1 = curl_exec($ch1);
        curl_close($ch1);

        // Update the timestamp
        file_put_contents('last_message_timestamp', time());
    }
} else {
    file_put_contents('last_message_timestamp', time());
}
?>
