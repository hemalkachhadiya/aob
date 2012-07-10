<?
function generatePages($page_size, $thepage, $query_string, $total=0, $current,$per_page = '10',$itemsName='products') {
    //per page count
    $index_limit = 10;

    //set the query string to blank, then later attach it with $query_string
    $query='';

    if(strlen($query_string)>0){
        $query = "/".$query_string;
    }

    //get the current page number example: 3, 4 etc: see above method description
    //$current = get_current_page();

    $total_pages=ceil($total/$page_size);
    $start=max($current-intval($index_limit/2), 1);
    $end=$start+$index_limit-1;

    echo '
<div class="pagination l">';

    if($current==1) {
        //echo '<span > Previous </span> ';
    } else {
        $i = $current-1;
        echo '<a class="prev" title="to page '.$i.'" rel="nofollow" href="'.$thepage.'/'.$i.$query.'">&larr; </a> ';
        //echo '<span >...</span> ';
    }

    if($start > 1) {
        $i = 1;
        echo '<a title="to page '.$i.'" href="'.$thepage.'/'.$i.$query.'">'.$i.'</a> ';
    }

    for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
        if($i==$current) {
            echo '<a href="#" class="selected">'.$i.'</a> ';
        } else {
            echo '<a title="to page '.$i.'" href="'.$thepage.'/'.$i.$query.'">'.$i.'</a> ';
        }
    }

    if($total_pages > $end){
        $i = $total_pages;
        echo '<a title="to page '.$i.'" href="'.$thepage.'/'.$i.$query.'">'.$i.'</a> ';
    }

    if($current < $total_pages) {
        $i = $current+1;
        //echo '<span >...</span> ';
        echo '<a class="next" title="to page '.$i.'" rel="nofollow" href="'.$thepage.'/'.$i.$query.'">&rarr;</a> ';
    } else {
       // echo '<span >Next </span> ';
    }

    //if nothing passed to method or zero, then dont print result, else print the total count below:
    if ($total != 0){
        //prints the total result count just below the paging
        echo '</div>';

//('.$total.' '.$itemsName.')

    }

}//end of method doPages()

//Both of the functions below required

function check_integer($which) {
    if(isset($_REQUEST[$which])){
        if (intval($_REQUEST[$which])>0) {
            //check the paging variable was set or not,
            //if yes then return its number:
            //for example: ?page=5, then it will return 5 (integer)
            return intval($_REQUEST[$which]);
        } else {
            return false;
        }
    }
    return false;
}//end of check_integer()

function get_current_page() {
    if(($var=check_integer('page'))) {
        //return value of 'page', in support to above method
        return $var;
    } else {
        //return 1, if it wasnt set before, page=1
        return 1;
    }
}//end of method get_current_page()
