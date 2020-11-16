<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Node;

class ApiController extends Controller
{
    public function api(Request $request){

       $error = inputValidation($request);

       $nodes = [];

       if($error == []){

            $node_id = $request -> node_id;
            
            $parent_node = Node::find($node_id);

            $p_left = $parent_node -> iLeft;

            $p_right = $parent_node -> iRight;

            $p_level = $parent_node -> level;

            $lng = $request -> language;

            if($request -> filled('page_num')){

                $page_num = $request -> page_num;
            } else {
               
                $page_num = 0;
            }


            if($request -> filled('page_size')){
                
                $page_size = $request -> page_size;
            }
            else {

                $page_size = 100;
            }


            if($request -> filled('search_keyword')){
                
                $keyword = $request -> search_keyword;
            }
            else {

                $keyword = '';
            }



            $child_nodes = Node::where('iLeft', '>', $p_left)
                                    -> where('iRight', '<', $p_right)
                                    -> where('level', '=', $p_level + 1)
                                    -> join('node_tree_names', 'node_tree_names.idNode', '=', 'node_tree.idNode')
                                    -> where('node_tree_names.language', '=', $lng)
                                    -> where('node_tree_names.name', 'like', '%' . $keyword . '%')
                                    -> get();


            foreach ($child_nodes as $child_node) {

                $children_count = Node::where('iLeft', '>', $child_node -> iLeft)
                                        -> where('iRight', '<', $child_node -> iRight)
                                        -> count();

                $child_node['children_count'] = $children_count;
            }


            if(count($child_nodes)){

                $max_page = ceil(count($child_nodes) / $page_size) - 1;

                if($page_num == 0){

                    $prev_page = '';
    
                    if($max_page >= 1){

                        $next_page = 1;
                    }else{

                        $next_page = '';
                    }

                }elseif($page_num > $max_page){
    
                    $error[] = 'Il numero di pagina è troppo alto. Ultima pagina utile:' . $max_page;
    
                    $prev_page = $max_page;
    
                    $next_page = '';
    
                } elseif($page_num == $max_page){
    
                    $prev_page = $page_num - 1;
    
                    $next_page = '';

                } elseif($page_num < $max_page){

                    $prev_page = $page_num - 1;

                    $next_page = $page_num + 1;
                }

            } else{

                $prev_page = '';

                $next_page = '';
            }


            $nodes = $child_nodes -> splice($page_num * $page_size, $page_size);

       } 


        return response() -> json([
            'nodes' => $nodes, 
            'errors' => $error,
            'prev_page' => $prev_page,
            'next_page' => $next_page
            ]);
    }
}



function inputValidation($request){

    $error = [];

    if (!$request -> filled('node_id') || !$request -> filled('language')) {

        $error[] = 'Parametri obbligatori mancanti';
        
    } 
    
    if(Node::find($request -> node_id) == null){

        $error[] = 'ID nodo non valido';
        

    } 
    
    if(!in_array($request -> language, ['italian', 'english'])){

        $error[] = 'Lingua non valida';
        
    }

    if ($request -> filled('page_num')) {
        
        $page_num = $request -> page_num;

        if(!is_numeric($page_num) || $page_num > 1000 || $page_num < 0 || is_float($page_num + 0) ){

            $error[] = 'Numero di pagina non valido';
        }
    }

    if ($request -> filled('page_size')) {
        
        $page_size = $request -> page_size;

        if(!is_numeric($page_size) || $page_size > 1000 || $page_size < 1 || is_float($page_size + 0)){

            $error[] = 'Dimensione pagina non valida';
        }
    }

    return $error;
}
