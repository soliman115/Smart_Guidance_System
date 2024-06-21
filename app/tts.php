<?php

// Function to generate an MP3 file from text
function generateMP3FromText($text, $lang = "en") {
    // Define a static filename
    $file = "output.mp3";
    $filepath = "audio/" . $file;

    //cut the first 200 char
    if (strlen($text) > 200) {
        //first 200 characters
        $text = substr($text, 0, 200);
    }

    // Check if the 'audio' directory exists, create it if it doesn't
    if (!is_dir("audio/")) {
        mkdir("audio/", 0777, true);
    } else {
        if (substr(sprintf('%o', fileperms('audio/')), -4) != "0777") {
            chmod("audio/", 0777);
        }
    }

    // Function to fetch the MP3 content using cURL with user-agent
    function fetchMp3($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
            return false;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            echo "HTTP error: " . $httpCode;
            return false;
        }

        curl_close($ch);
        return $response;
    }

    // Generate the URL for the Google Translate TTS service
    $url = 'http://translate.google.com/translate_tts?ie=UTF-8&q=' . urlencode($text) . '&tl=' . $lang . '&total=1&idx=0&textlen=5&prev=input&client=tw-ob';

    // Fetch the MP3 content
    $mp3 = fetchMp3($url);

    // Write the new MP3 file to the 'audio' directory
    if ($mp3 !== false) {
        if (file_put_contents($filepath, $mp3) === false) {
            echo "Failed to write the MP3 file";
            return false;
        } else {
            echo "MP3 file written successfully<br>";
            return $filepath; // Return the filepath if successful
        }
    } else {
        echo "Failed to fetch the MP3 content";
        return false;
    }
}

// Example usage of the function
$text = "You are 50 meters away from General manager's office . toward E proceed 10 meters toward northeast . Go northeast towards the second room in the corridor on the left. You have reached your final stop, General manager's office.";
$lang = "en";
generateMP3FromText($text, $lang);

$length = strlen($text);
echo "The length of the string is: " . $length;

?>
