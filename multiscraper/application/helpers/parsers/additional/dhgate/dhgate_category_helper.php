<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	//echo $html;exit;
		$out = array();
	
		
		$instruction = 'div#proList h3 a';
        $parser = new nokogiri($html);
        $res = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
        if(isset($res) && is_array($res) && count($res) > 0){
            foreach($res as $pos_product){
                if(isset($pos_product['href']) && !is_array($pos_product['href']) && strlen($pos_product['href']) > 0 && trim($pos_product['href']) !== "#"){
                    $out[] = $pos_product['href'];
                }
            }
        
        }
        
        $instruction = 'div#proGallery h3 a';
        $parser = new nokogiri($html);
        $res = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
        if(isset($res) && is_array($res) && count($res) > 0){
            foreach($res as $pos_product){
                if(isset($pos_product['href']) && !is_array($pos_product['href']) && strlen($pos_product['href']) > 0 && trim($pos_product['href']) !== "#"){
                    $out[] = $pos_product['href'];
                }
            }
        
        }
        
        $out = array_unique($out);
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        
        return $out;
}


function parse_next_page($html , $task){
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		if(isset($next[0]['href']) && !is_array($next[0]['href'])){
		    $result = $next[0]['href'];
		    //echo $result;exit;
		    return $result;
		}
        return false;
}

