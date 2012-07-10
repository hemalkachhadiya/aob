<?
 function setFormValue($name,$MainObject,$preFill = false){

    $local = set_value ($name);
    if ($local){
        return $local;
    }else{
        if ($MainObject->$name)
            return $MainObject->$name;
        else
            return $preFill;
    }
}
function displayPhoto($photo,$folder = 'users',$default = USER_DEFAULT_PHOTO){
    if ($photo)
    {
        return "/img/$folder/$photo";
    }else{
        return $default;
    }
}
function setLink($user,$id='id',$nickname='nickname'){
    if ($user->$nickname){
        return '/user/'.$user->$nickname;
    }else{
        return '/user/'.$user->$id;
    }
}

function displayDepartments($list,$id = 'id'){
        if (!empty($list)){
            $tmp = array();
            foreach ($list as $department)  {
                $tmp[] = " <a href='/department/{$department->$id}' >{$department->name}</a> ";
            }
            //var_dump ($tmp);
            return join("/",$tmp);
        }
}


function setDate($item){
    setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'Russian_Russia.UTF-8');
    return strftime ("%d %B %Y",strtotime($item));
}


function getProjectDate($date){
    setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'Russian_Russia.UTF-8');
    $inDate = date('d/m/Y', strtotime($date));
    if($inDate == date('d/m/Y')) {
        $modifier = " Сегодня ";
     //   return strftime (" Сегодня  в ",strtotime($date));
    } else if($inDate == date('d/m/Y',strtotime('now') - (24 * 60 * 60))) {
        $modifier = " Вчера ";
     //   return strftime (" Вчера  в ",strtotime($date));
    }else{
        $modifier = " %d %B %Y ";
     //   return strftime (" %d %B %Y   ",strtotime($date));
    }
    return strftime (" $modifier в %H:%M ",strtotime($date));
}



function declension($int, $expressions)
    {
        if (count($expressions) < 3) $expressions[2] = $expressions[1];
        settype($int, "integer");
        $count = $int % 100;
        if ($count >= 5 && $count <= 20) {
            $result = $expressions['2'];
        } else {
            $count = $count % 10;
            if ($count == 1) {
                $result = $expressions['0'];
            } elseif ($count >= 2 && $count <= 4) {
                $result = $expressions['1'];
            } else {
                $result = $expressions['2'];
            }
        }
        return $result;
    }
function transformToFC($currencyAmountRu){
    $result = round(($currencyAmountRu/30), 2);
    return $result." FC";
}
function setOptions($list,$value = 'id',$name = 'description',$price = true,$discount = false){

    foreach($list as $item){

        if ($price){
            if ($discount){
                $price = $item->price - $item->price*$discount/100;
            }else{
                $price = $item->price;
            }
            $price = " &mdash; ".transformToFC($price);

        }else{
            $price = '';
        }
        echo "<option value='{$item->$value}'>".smarty_modifier_mb_truncate($item->$name,30)." ".$price."</option>";
    }
}
function displayExpertPanel($item,$expert='expert',$type='type',$addClass = ''){

    if ($item->$expert){
        if ($item->$type == 2) {
            return '<span class="exp employer big-label '.$addClass.'" >expert</span>';
        } else if ($item->$type == 1) {
            return '<span class="exp '.$addClass.'" >expert</span>';
        }
    }
}
function displayPlainExpertPanel($item,$expert='expert',$type='type',$addClass = ''){

    if ($item->$expert){
        if ($item->$type == 2) {
            return '<span class="exp employer big-label '.$addClass.'" ></span>';
        } else if ($item->$type == 1) {
            return '<span class="exp '.$addClass.'" ></span>';
        }
    }
}
function displayFileName($fileName){
    if (!empty($fileName)){
        return $fileName;
    }else{
        return "Выберите...";
    }
}