<?php


function mspro_dhgate_title($html){
	   $instruction = 'h1[itemprop=name]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        $instruction = 'h2.fn';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        $instruction = 'h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        return '';
}

function mspro_dhgate_description($html){
	    $res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		/*$temp  = $pq->find('div#itemDescription');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}*/
		
		$temp  = $pq->find('div.returns');
		foreach ($temp as $block){
		    $res .= $temp->html().'<br />';
		}
		$temp  = $pq->find('div.description');
		foreach ($temp as $block){
		    $res .= $temp->html().'<br />';
		}
		$temp  = $pq->find('div.detailed-description');
		foreach ($temp as $block){
		    $res .= $temp->html().'<br />';
		}
		
		//echo $res;exit;
		$instruction = 'div#specificationInfo';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';
	    if(isset($data[0]['data-url']) && !is_array($data[0]['data-url'])){
	    	$ajaxRes = false;
	    	@$ajaxRes = getUrl($data[0]['data-url']);
	    	//echo $ajaxRes;exit;
	    	if($ajaxRes){
	    		$res .= '<div>' . $ajaxRes . '</div>';
	    	}
	    }
		
		$res = preg_replace(array("'<a [^>]*?>.*?</a>'si"), Array(""), $res);
		
		// add delivery time
		$t_res = explode('<div class="delestimate Ships-within">' , $html);
		if(count($t_res) > 1){
		    $t_res = explode('<div' , $t_res[1] , 2);
		    if(count($t_res) > 1){
		        $res .= '<b><p style="font-size: 1.2em;">' . strip_tags($t_res[0]) . '</p></b>';
		    }
		}
		
		//echo $res;exit;
		
		return $res;
}


function mspro_dhgate_price($html){
        $res = explode('<span id="js-priceTips"><span>' , $html);
        if(count($res) > 1){
            $res = explode('</span>' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode('<span itemprop="highPrice">' , $html);
        if(count($res) > 1){
            $res = explode('</span>' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
	    $res = explode('"lowPrice": "' , $html);
        if(count($res) > 1){
        	$res = explode('"' , $res[1] , 2);
        	if(count($res) > 1){
        		$price = preg_replace("/[^0-9.]/", "",  $res[0]);
        		return (float) $price;
        	} 
        }
        return '';
}


function mspro_dhgate_sku($html){
        $instruction = 'input#selectedSkuVal';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';
 		unset($parser);
	    if (isset($data[0]['value']) && !is_array($data[0]['value'])  ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['value']));
        }
       
		return ''; 
}

function mspro_dhgate_model($html){
	return mspro_dhgate_sku($html);
}


function mspro_dhgate_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);
			}	 
       }
       return '';
}

function mspro_dhgate_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return  mspro_dhgate_meta_description($html);
}


function mspro_dhgate_main_image($html){
	$arr = mspro_dhgate_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_dhgate_other_images($html){
	$arr = mspro_dhgate_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_dhgate_get_images_arr($html){
		$out = array();
	
    	$instruction = 'ul#simgList li';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['b-init']) && !is_array($pos_image['b-init'])){
    				$out[] = $pos_image['b-init'];
    			}elseif(isset($pos_image['s-init']) && !is_array($pos_image['s-init'])){
    				$out[] = $pos_image['s-init'];
    			}
    		}
    	}
    	
    	return $out;
}



function mspro_dhgate_options($html){
    $out = array();

    $instruction = 'tr.pro_attr_content';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['th'][0]['span']['#text']) && !is_array($pos_option['th'][0]['span']['#text']) &&  isset($pos_option['td'][0]['ul'][0]['li']) && is_array($pos_option['td'][0]['ul'][0]['li']) && count($pos_option['td'][0]['ul'][0]['li']) > 0){
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['th'][0]['span']['#text']);
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['td'][0]['ul'][0]['li'] as $option_value){
                    if(isset($option_value['span'][0]['#text']) && !is_array($option_value['span'][0]['#text'])){
                        $OPTION['values'][] = array('name' => $option_value['span'][0]['#text'] , 'price' => 0);
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }
        }
    }
    
    $instruction = 'div.item_box';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        $originalPrice =  mspro_dhgate_price($html);
        foreach($res as $pos_option){
            if(isset($pos_option['option_id']) &&
               (isset($pos_option['class']) && strpos($pos_option['class'] , 'attr') > 0) &&
               ( (isset($pos_option['div'][0]['#text']) && !is_array($pos_option['div'][0]['#text'])) || (isset($pos_option['div'][0]['#text'][0]) && !is_array($pos_option['div'][0]['#text'][0])) ) &&
               isset($pos_option['a']) && is_array($pos_option['a']) && count($pos_option['a']) > 0)
            {
                    $OPTION = array();
                    if(is_array($pos_option['div'][0]['#text']) && isset($pos_option['div'][0]['#text'][0]) && !is_array($pos_option['div'][0]['#text'][0])){
                        $name = str_replace( array(":") , array("") , $pos_option['div'][0]['#text'][0]);
                    }else{
                        $name = str_replace( array(":") , array("") , $pos_option['div'][0]['#text']);
                    }
                    $OPTION['name'] = $name;
                    $OPTION['type'] = "select";
                    $OPTION['required'] = true;
                    $OPTION['values'] = array();
                    foreach($pos_option['a'] as $option_value){
                        if(isset($option_value['ori_name']) && !is_array($option_value['ori_name'])){
                            $price = 0;
                            if(isset($option_value['oriPrice']) && ((float) $option_value['oriPrice'] < 0 ||  (float) $option_value['oriPrice'] > 0)){
                                $price = (float) $option_value['oriPrice'];
                            }elseif(isset($option_value['oriprice']) && isset($option_value['oriprice']) && isset($option_value['price_prefix']) ){
                                if(strpos($option_value['price_prefix'] , "+") > -1){
                                    $price = (float) $option_value['oriprice'];
                                }else{
                                    $price = 0 - (float) $option_value['oriprice'];
                                }
                            }
                            $OPTION['values'][] = array('name' => $option_value['ori_name'] , 'price' => $price);
                        }
                    }
                    if(count($OPTION['values']) > 0){
                        $out[] = $OPTION;
                    }
                }
        }
    }
    
    
    $instruction = 'div.optionscon div.options-list ul';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if(isset($res[0]['li']) && is_array($res[0]['li']) && count($res[0]['li']) > 0 && isset($res[0]['input'][0]['value']) && !is_array($res[0]['input'][0]['value'])){
        $OPTION = array();
        $opt_name = $res[0]['input'][0]['value'];
        $t_res = explode("|" , $res[0]['input'][0]['value']);
        if(count($t_res) > 1){
            $opt_name = trim($t_res[0]);
        }
        $OPTION['name'] = str_replace( array(":") , array("") , $opt_name);
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        foreach($res[0]['li'] as $option_value){
            if(isset($option_value['span']['#text']) && !is_array($option_value['span']['#text'])){
                $OPTION['values'][] = array('name' => $option_value['span']['#text'] , 'price' => 0);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }
    
    $instruction = 'div.optionscon ul.js-selectionWrapper';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['li']) && is_array($pos_option['li']) && count($pos_option['li']) > 0){
                $OPTION = array();
                $opt_name = isset($pos_option['li'][0]['input'][0]['value'])?$pos_option['li'][0]['input'][0]['value']:"Color";
                $t_res = explode("|" , $opt_name);
                if(count($t_res) > 1){
                    $opt_name = trim($t_res[0]);
                }
                $OPTION['name'] = str_replace( array(":") , array("") , $opt_name);
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['li'] as $option_value){
                    if(isset($option_value['span'][0]['var']['#text']) && !is_array($option_value['span'][0]['var']['#text'])){
                        $OPTION['values'][] = array('name' => $option_value['span'][0]['var']['#text'] , 'price' => 0);
                    }elseif(isset($option_value['span'][0]['img']['title']) && !is_array($option_value['span'][0]['img']['title'])){
                        $OPTION['values'][] = array('name' => $option_value['span'][0]['img']['title'] , 'price' => 0);
                    }elseif(isset($option_value['csname']) && !is_array($option_value['csname'])){
                        $OPTION['values'][] = array('name' => $option_value['csname'] , 'price' => 0);
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }
        }
    }
    
    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
    
}

function mspro_dhgate_attributes($html){
    $out = array();

    $instruction = 'div.item-specifics';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    foreach($data as $attrGroup){
        //echo $i;
        if(isset($attrGroup['div'][0]['#text']) && !is_array($attrGroup['div'][0]['#text']) && isset($attrGroup['h2'][0]['span']) && is_array($attrGroup['h2'][0]['span']) && count($attrGroup['h2'][0]['span']) > 0 ){
            foreach($attrGroup['h2'][0]['span'] as $posAttr){
                $ATTR = array();
                $ATTR['group'] = trim(str_replace( array(":") , array("") , $attrGroup['div'][0]['#text']));
                if(isset($posAttr['strong'][0]['#text']) && !is_array($posAttr['strong'][0]['#text']) && isset($posAttr['#text'][0]) && !is_array($posAttr['#text'][0])){
                    $ATTR['name'] = trim(str_replace( array(":") , array("") , $posAttr['strong'][0]['#text']));
                    $ATTR['value'] = trim(str_replace( array(":") , array("") , $posAttr['#text'][0]));
                    $out[] = $ATTR;
                }
                 
            }
        }
    }

    //echo '<pre>'.print_r($out , 1).'</pre>';exit;

    return $out;
}

/*function mspro_dhgate_noMoreAvailable($html){
	return false;
}*/
