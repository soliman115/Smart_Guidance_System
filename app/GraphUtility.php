<?php
namespace App;
use App\Models\Route;
use App\Models\Region;
use App\Models\Place;

//text-to-audio generator
// require '../vendor/autoload.php'; // Load Google Cloud PHP client library

// use Google\Cloud\TextToSpeech\V1\AudioConfig;
// use Google\Cloud\TextToSpeech\V1\AudioEncoding;
// use Google\Cloud\TextToSpeech\V1\SynthesisInput;
// use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
// use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
 
// use IBM\Watson\TextToSpeech\TextToSpeech;
class GraphUtility
{
    private static $graph = null;

    //constructGraphFromDatabase
    public static function constructGraphFromDatabase()
    {
        // Fetch all routes from the database
        $routes = Route::all();

        // Initialize an empty graph
        $graph = [];

        // Populate the graph with routes data
        foreach ($routes as $route) {
            // Add source node if not exists
            if (!isset($graph[$route->source])) {
                $graph[$route->source] = [];
            }

            // Add destination node if not exists
            if (!isset($graph[$route->destination])) {
                $graph[$route->destination] = [];
            }

            // Add next step and distance to the source node
            $graph[$route->source][$route->next_step] = $route->distance;

            // If the next step is the destination, but it's not the same as the source,
            // don't set the distance to 100
            if ($route->next_step === $route->destination && $route->next_step !== $route->source) {
                // Distance to the destination should not necessarily be 100
                // Instead, let's set it to the actual distance specified in the route
                $graph[$route->source][$route->destination] = $route->distance; 
            }
        }
        // Store the constructed graph for later use
        self::$graph = $graph;
        return $graph;
    }//end constructGraphFromDatabase

    public static function findShortestPath($source, $destination)
    {
        $finalDestinationId=$destination;
        $place= Place::find($destination);
        $destination_id = $place->Region;//destination is the destination region
        $source_id=Region::find($source);//source is the source region
        $destination = $destination_id->name;
        $source= $source_id->name;
        // Construct the graph if not already constructed
        self::constructGraphFromDatabase();

        // Get the constructed graph
        $graph = self::$graph;

        // Check if source and destination nodes exist in the graph
        if (!isset($graph[$source]) || !isset($graph[$destination])) {
            // Handle case where source or destination is not found in the graph
            return "$source or $destination is not found in the graph";
        }

        // Initialize distances from source to all other nodes
        $distances = array_fill_keys(array_keys($graph), INF);
        $distances[$source] = 0;

        // Initialize an array to keep track of visited nodes
        $visited = array();

        // Initialize an array to keep track of predecessors
        $predecessors = array();

        // Main loop to traverse the graph
        while (true) {
            // Find the node with the minimum distance among unvisited nodes
            $minDistance = INF;
            $minNode = null;
            foreach ($graph as $node => $_) {
                if (!isset($visited[$node]) && $distances[$node] < $minDistance) {
                    $minDistance = $distances[$node];
                    $minNode = $node;
                }
            }

            // If all nodes are visited or unreachable, break the loop
            if ($minNode === null || $minDistance === INF) {
                break;
            }

            // Mark the current node as visited
            $visited[$minNode] = true;

            // Process neighbors of the current node
            foreach ($graph[$minNode] as $neighbor => $distance) {
                // Update distance if shorter path found
                $newDistance = $distances[$minNode] + $distance;
                if ($newDistance < $distances[$neighbor]) {
                    $distances[$neighbor] = $newDistance;
                    // Update predecessor for the neighbor node
                    $predecessors[$neighbor] = $minNode;
                }
            }
        }

        // Check if the destination is reachable
        if ($distances[$destination] === INF) {
            // Destination is unreachable from the source
            return "Destination is unreachable from the source";
        }

        // Reconstruct the shortest path
        $path = array();
        $currentNode = $destination;
        while ($currentNode !== $source) {
            $path[] = $currentNode;
            $currentNode = $predecessors[$currentNode];
        }
        $path[] = $source;

        // Reverse the path to start from the source
        $path = array_reverse($path);
        array_push($path,$finalDestinationId );

        // Initialize the result array for node-distance
        $nodeDistanceDirArray = [];

        // Initialize total distance
        $totalDistance = 0;
        // Calculate the Euclidean distance between the last node and the node before it
        if (count($path) > 1) {
            $place = Place::find($finalDestinationId);

            // Check if $place is not null
            if ($place) {
                //echo "Place found: " . json_encode($place) . PHP_EOL; // Debug output


                // Check if last region Id is not null
                if ($destination_id) {
                // echo "last region found: " . json_encode($destination_id) . PHP_EOL; // Debug output
                    $finalX = $place->x_coordinate;
                    $finalY = $place->y_coordinate;
                    $sourceX = $destination_id->x_coordinate;
                    $sourceY = $destination_id->y_coordinate;

                    // Calculate the Euclidean distance
                    $euclideanDistance = round(sqrt(pow($finalX - $sourceX, 2) + pow($finalY - $sourceY, 2)));

                    // Update the distance_to_next for the last node
                    //$nodeDistanceArray[count($nodeDistanceArray) - 1]['distance_to_next'] = $euclideanDistance;
                } else {
                    return "Node before the last node of $destination_id is not found";
                }
            } else {
                return "Final destination place os id $finalDestinationId is not found";
            }
        }

        for ($i = 0; $i < count($path) - 1; $i++) {
            $currentNode = $path[$i];
            $nextNode = $path[$i + 1];
        
            // Check if it's the last element in the path
            if ($i === count($path) - 2 && isset($euclideanDistance)) {
                // Use the precalculated Euclidean distance
                $distance = $euclideanDistance;
                $dir=null;
            } else {
                // Calculate the distance between current and next nodes
                $distance = $graph[$currentNode][$nextNode];
                $dir = Route::where('source', $path[$i])->where('next_step', $path[$i + 1])->first()->direction;
            }
        
            // Add the node and distance to the result array
            $nodeDistanceDirArray[] = [
                'node' => $currentNode,
                'distance_to_next' => $distance,
                'direction_to_next'=>$dir
            ];
        
            // Add distance to the total distance
            $totalDistance += $distance;
        }

        // Return the shortest path, node-distance array, and total distance
        return [
            'path' => $path,
            'node_distance_direction_array' => $nodeDistanceDirArray,
            'total_distance' => $totalDistance
        ];
    }// end get the path

    
    public static function generateNavigationInstructions($navigationData) {
        $path = $navigationData['path'];
        $nodeDistanceDirArray = $navigationData['node_distance_direction_array'];
        $totalDistance = $navigationData['total_distance'];
    
        $finalDestination = end($path); // Last node is the final destination
        $finalDestinationName = place::find($finalDestination)->name;
        $finalDestinationGuideWord = place::find($finalDestination)->guide_word;
        // Synonyms dictionary
        $synonyms = [
            'start' => ['commence', 'begin', 'embark', 'set out'],
            'destination' => ['endpoint', 'goal', 'target', 'final stop'],
            'walk' => ['stroll', 'hike', 'march', 'advance', 'proceed'],
            'towards' => ['in the direction of', 'to', 'heading to', 'moving toward', 'approaching'],
            'direction' => ['toward', 'to', 'into', 'onto', 'in the direction of'],
            'you' => ['visitor', 'traveler', 'explorer', 'adventurer']
        ];
    
        // Map numeric direction codes to cardinal directions
        $directionsMap = ['','north', 'northeast', 'east', 'southeast', 'south', 'southwest', 'west', 'northwest'];
    
        $instructions = "You are $totalDistance meters away from $finalDestinationName . ";
        $previousWord = ''; // To keep track of the previously used word
    
        foreach ($nodeDistanceDirArray as $index => $nodeData) {
            $currentNode = $path[$index]; // Get the current node from the path
    
            $distance = $nodeData['distance_to_next'];
            $numericDirection = $nodeData['direction_to_next'];
    
            // Use the numeric direction code to get the cardinal direction
            $direction = isset($directionsMap[$numericDirection]) ? $directionsMap[$numericDirection] : '';
    
            // Use the previously used word if the current word is the same
            $currentWord = 'direction'; // Default word
            if (isset($synonyms[$currentWord])) {
                $currentWord = $synonyms[$currentWord][array_rand($synonyms[$currentWord])];
            }
    
            if ($direction != null) {
                // Use the current word, direction, and point in the instructions
                $instructions .= "$currentWord $currentNode ";
                $instructions .= "proceed $distance meters ";
                $instructions .= "{$synonyms['direction'][array_rand($synonyms['direction'])]} $direction . ";
            } else {
                $destinationSynonym = $synonyms['destination'][array_rand($synonyms['destination'])];
                $instructions .= "$finalDestinationGuideWord. ";
                $instructions .= "You have reached your $destinationSynonym, $finalDestinationName. ";
            }
        }
    
        return [           
                'path' => $path,
                'instructions'=>$instructions,
                'node_distance_direction_array'=>$nodeDistanceDirArray
                        
                ];
    }
    

    // Function to generate an MP3 file from text
    public static function generateMP3FromText($text, $lang = "en") {
        // Generate a unique filename using MD5 hash
        $file = md5($lang . "?" . urlencode($text)) . ".mp3";
        $filepath = "audio/" . $file;

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

        // Delete the old MP3 file if it exists
        if (file_exists($filepath)) {
            if (!unlink($filepath)) {
                echo "Failed to delete old MP3 file<br>";
                return false;
            }
        }

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
    // end generate an MP3 file from text

}
?>