<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Node;

class ApiController extends Controller
{
    public function api(Request $request){

       $error = inputValidation($request); // validazione dell'input. restituisce un'array di stringhe di errori


       // valori di default
       $nodes = [];
       $prev_page = '';
       $next_page = '';

       if($error == []){ // se non sono stati riscontrati errori nell'input si esegue il resto della funzione

            // prendere i dati dall'input
            $node_id = $request -> node_id;
            
            $parent_node = Node::find($node_id);

            $p_left = $parent_node -> iLeft;

            $p_right = $parent_node -> iRight;

            $p_level = $parent_node -> level;

            $lng = $request -> language;

            // per gli input opzionali si setta un valore se sono definiti altrimenti si attribuisce un valore di default
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

                $keyword = ''; // in caso di stringa vuota nella query verranno accettati tutti i record
            }


            // query al db
            $child_nodes = Node::where('iLeft', '>', $p_left)
                                    -> where('iRight', '<', $p_right)
                                    -> where('level', '=', $p_level + 1)
                                    -> join('node_tree_names', 'node_tree_names.idNode', '=', 'node_tree.idNode')
                                    -> where('node_tree_names.language', '=', $lng)
                                    -> where('node_tree_names.name', 'like', '%' . $keyword . '%')
                                    -> get();

            // per ogni nodo calcolo il numero di nodi figli salvo il valore aggiungendo un atributo children_count
            foreach ($child_nodes as $child_node) {

                $children_count = Node::where('iLeft', '>', $child_node -> iLeft)
                                        -> where('iRight', '<', $child_node -> iRight)
                                        -> count();

                $child_node['children_count'] = $children_count;
            }


            if(count($child_nodes)){ // il calcolo delle pagine (next e prev) si esegue solo se la query ha dato dei risultati

                $max_page = ceil(count($child_nodes) / $page_size) - 1; // calcolo della pagina massima

                // prev_page e next_page assumono valori diversi a seconda di che pagina stiamo visualizzando (0, oltre la massima, l'ultima) e quante sono le pagine massime. In tutti i casi esclusi da questa serie di if si mantengono i valori di default assegnati all'inizio 
                if($page_num == 0){

                    $prev_page = '';
    
                    if($max_page >= 1){

                        $next_page = 1;
                    }else{

                        $next_page = '';
                    }

                }elseif($page_num > $max_page){
    
                    $error[] = 'Il numero di pagina è troppo alto. Ultima pagina utile: ' . $max_page;
    
                    $prev_page = $max_page;
    
                    $next_page = '';
    
                } elseif($page_num == $max_page){
    
                    $prev_page = $page_num - 1;
    
                    $next_page = '';

                } elseif($page_num < $max_page){

                    $prev_page = $page_num - 1;

                    $next_page = $page_num + 1;
                }

            }

            // attraverso splice seleziono solo i report da visualizzare nella "pagina".
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
    
    if(!in_array($request -> language, ['italian', 'english'])){ // solo english e italian sono accettati come valori di language

        $error[] = 'Lingua non valida';
        
    }

    // validazione di page_num e page_num solo se sono filled(settati e non vuoti), se sono null va bene tanto varrà assegnato il valore di default
    if ($request -> filled('page_num')) {
        
        $page_num = $request -> page_num;

        if(!is_numeric($page_num) || $page_num > 1000 || $page_num < 0 || is_float($page_num + 0) ){ // deve essere numerico, compreso tra 0 e 1000 e non un float (si utilizza is_float( $num + 0) è un trick per forzare php a trasformare la stringa in un numero e poi verificare che sia float)

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
