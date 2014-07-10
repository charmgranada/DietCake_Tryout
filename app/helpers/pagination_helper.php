<?php
    function pagination($thread_id, $totalCount,$pn) 
    {
        define("ROWS_PER_PAGE", 5);
        $pagination = array();
        $last_page = ceil($totalCount/ROWS_PER_PAGE);
        if ($last_page < 1) {
            $last_page = 1;
        }
        // INITIALIZATION OF THE CURRENT PAGE //
        $cur_page = 1;
        if (!is_null($pn)) {
            $cur_page = preg_replace('/[^0-9]/', '', $pn);
        }
        if ($cur_page < 1) {
            $cur_page = 1;
        } else if ($cur_page > $last_page) {
            $cur_page = $last_page;
        }
        // SETS THE LIMIT PER PAGE //    
        $limit_page = ($cur_page - 1) * ROWS_PER_PAGE;
        $limit = $limit_page . ',' . ROWS_PER_PAGE;
        // PAGINATION CONTROLS WILL ONLY APPEAR IF THERE ARE MORE THAN 1 PAGES //   
        $controls = '';
        if ($last_page != 1) {
            // THESE ARE THE PAGINATION CONTROLS SHOWN ON THE LEFT OF THE CURRENT PAGE //
            if ($cur_page > 1) {            
                $previous = $cur_page - 1;
                $controls .= "<a href='" . url('comment/view', array('thread_id' =>  $thread_id, 'pn' => $previous)) . "'> 
                    Previous </a> &nbsp; &nbsp;";
                for ($i = $cur_page - 4 ; $i < $cur_page ; $i++) {
                    if ($i > 0) { 
                        $controls .= "<a href='" . url('comment/view', array('thread_id' =>  $thread_id, 'pn' => $i)) . "'>
                            $i</a> &nbsp; ";
                    }
                }
            }
            // ECHO THE CURRENT PAGE SINCE IT DOESN'T NEED A LINK ANYMORE 
            $controls .=  "" . $cur_page . "&nbsp ";
            // THESE ARE THE PAGINATION CONTROLS SHOWN ON THE RIGHT OF THE CURRENT PAGE //        
            for ($i = $cur_page + 1 ; $i <= $last_page ; $i++) { 
                $controls .= "<a href='" . url('comment/view', array('thread_id' =>  $thread_id, 'pn' => $i)) . "'>
                    $i</a> &nbsp; ";
                if ($i >= $cur_page + 4) {
                    break;
                }
            }
            if ($cur_page != $last_page) {
                $next = $cur_page + 1;
                $controls .= " &nbsp; &nbsp; 
                    <a href='" . url('comment/view', array('thread_id' =>  $thread_id, 'pn' => $next)) . "'> Next </a> ";
            }
        }

        $pagination['last_page'] = $last_page;
        $pagination['cur_page'] = $cur_page;
        $pagination['limit'] = $limit;
        $pagination['controls'] = $controls;
        return $pagination; 
    }
