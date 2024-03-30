<?php
namespace App;
use App\Models\Route;
use App\Models\Region;
use App\Models\Place;
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


    // //generateNavigationInstructions
    // public static function generateNavigationInstructions($navigationData) {
    //     $path = $navigationData['path'];
    //     $nodeDistanceDirArray = $navigationData['node_distance_direction_array'];
    //     $totalDistance = $navigationData['total_distance'];
    
    //     $currentNode = $path[0]; // First node is the current node
    //     $finalDestination = end($path); // Last node is the final destination
    //     $instructions = "You are currently at node $currentNode. ";
    
    //     $remainingDistance = $totalDistance;
    //     foreach ($nodeDistanceDirArray as $nodeData) {
    //         $distance = $nodeData['distance_to_next'];
    //         $direction = $nodeData['direction_to_next'];
    
    //         if ($distance > 0) {
    //             $instructions .= "Walk $distance meters ";
    //             if ($direction) {
    //                 $instructions .= "in the direction of $direction. ";
    //             } else {
    //                 $instructions .= "forward. ";
    //             }
    //             $remainingDistance -= $distance;
    //         } else {
    //             $instructions .= "You have reached your destination, node $finalDestination. ";
    //         }
    //     }
    
    //     if ($remainingDistance > 0) {
    //         $instructions = "You are $remainingDistance meters away from your destination. " . $instructions;
    //     }
    
    //     return [
    //         'path' => $path,
    //         'instructions'=>$instructions
    //     ];
    // } //end generateNavigationInstructions
    




    public static function generateNavigationInstructions($navigationData) {
        $path = $navigationData['path'];
        $nodeDistanceDirArray = $navigationData['node_distance_direction_array'];
        $totalDistance = $navigationData['total_distance'];
    
        $finalDestination = end($path); // Last node is the final destination
    
        // Convert numeric directions to cardinal directions
        $directionsMap = ['','north', 'northeast', 'east', 'southeast', 'south', 'southwest', 'west', 'northwest'];
    
        $instructions = "You are $totalDistance meters away from your destination. ";
        
        foreach ($nodeDistanceDirArray as $index => $nodeData) {
            $currentNode = $path[$index]; // Get the current node from the path
    
            $distance = $nodeData['distance_to_next'];
            $numericDirection = $nodeData['direction_to_next'];
            $direction = isset($directionsMap[$numericDirection]) ? $directionsMap[$numericDirection] : '';
    
            if ($distance > 0) {
                if ($index === 0) {
                    $instructions .= "You will start from node $currentNode. ";
                }
    
                $instructions .= "Walk $distance meters ";
                if ($direction) {
                    $instructions .= "in the direction of $direction. ";
                } else {
                    $instructions .= "forward. ";
                }
            } 
                
        }  $instructions .= "You have reached your destination, node $finalDestination. ";
        return [
                    'path' => $path,
                    'instructions'=>$instructions,
                    'node_distance_direction_array'=>$nodeDistanceDirArray
                ];
    } //end generateNavigationInstructions






}
