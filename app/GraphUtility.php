<?php
namespace App;
use App\Models\Route;
use App\Models\Region;
use App\Models\Place;
class GraphUtility
{
    private static $graph = null;
// //constructGraphFromDatabase
//     public static function constructGraphFromDatabase()
//     {
//         // Fetch all routes from the database
//         $routes = Route::all();

//         // Initialize an empty graph
//         $graph = [];

//         // Populate the graph with routes data
//         foreach ($routes as $route) {
//             // Add source node if not exists
//             if (!isset($graph[$route->source])) {
//                 $graph[$route->source] = [];
//             }

//             // Add destination node if not exists
//             if (!isset($graph[$route->destination])) {
//                 $graph[$route->destination] = [];
//             }

//             // Add next step and distance to the source node
//             $graph[$route->source][$route->next_step] = $route->distance;

//             // If the next step is the destination, add it to the graph as well
//             if ($route->next_step === $route->destination) {
//                 $graph[$route->source][$route->destination] = 0; // Distance from source to destination is 0
//             }
//         }

//         // Store the constructed graph for later use
//         self::$graph = $graph;
//         return $graph;
//     }//end constructGraphFromDatabase







// //constructGraphFromDatabase
// public static function constructGraphFromDatabase()
// {
//     // Fetch all routes from the database
//     $routes = Route::all();

//     // Initialize an empty graph
//     $graph = [];

//     // Populate the graph with routes data
//     foreach ($routes as $route) {
//         // Add source node if not exists
//         if (!isset($graph[$route->source])) {
//             $graph[$route->source] = [];
//         }

//         // Add destination node if not exists
//         if (!isset($graph[$route->destination])) {
//             $graph[$route->destination] = [];
//         }

//         // Add next step and distance to the source node
//         $graph[$route->source][$route->next_step] = $route->distance;

//         // If the next step is the destination, but it's not the same as the source,
//         // don't set the distance to 0
//         if ($route->next_step === $route->destination && $route->next_step !== $route->source) {
//             // Distance to the destination should not necessarily be 0
//             // You can either leave it unspecified or set it to a default value
//             // For now, let's set it to a default value of 100 (you can adjust this as needed)
//             $graph[$route->source][$route->destination] = 100; 
//         }
//     }

//     // Store the constructed graph for later use
//     self::$graph = $graph;
//     return $graph;
// }//end constructGraphFromDatabase






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

    //findShortestPath
    public static function findShortestPath($source, $destination)
    {   $final_destination=$destination;
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
        array_push($path,$final_destination );

        // Return the shortest path and its distance
        return array('path' => $path, 'distance' => $distances[$destination]);
    } //end findShortestPath

}
