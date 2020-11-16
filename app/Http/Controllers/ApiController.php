<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Node;

class ApiController extends Controller
{
    public function api(Request $request){

        $node_id = $request -> node_id;

        $parent_node = Node::findOrFail($node_id);

        $p_left = $parent_node -> iLeft;

        $p_right = $parent_node -> iRight;

        $p_level = $parent_node -> level;

        $lng = $request -> language;

        $child_nodes = Node::where('iLeft', '>', $p_left)
                                -> where('iRight', '<', $p_right)
                                -> where('level', '=', $p_level + 1)
                                -> join('node_tree_names', 'node_tree_names.idNode', '=', 'node_tree.idNode')
                                -> where('node_tree_names.language', '=', $lng)
                                -> get();


        return response() -> json($child_nodes);
    }
}
