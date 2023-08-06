<?php 
class db{
    public static $conn;
    public static function connect($args=[]){
        $DATABASE_HOST=$args['db_host'];
        $DATABASE_USER=$args['db_user'];
        $DATABASE_PASS=$args['db_password'];
        $DATABASE_NAME=$args['db_name'];
        $conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        if (mysqli_connect_errno()) {
            // If there is an error with the connection, stop the script and display the error.
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
                }
        self::$conn= $conn;
        return self::$conn;
    }
    
    public static function select($sql,$params=null,$types = null){
        $stmt = self::$conn->prepare($sql);
        if(isset($types,$params)){
            if(isset($types,$params))$stmt->bind_param($types, ...$params);
        }else if(isset($params)){
            $types=self::paramTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        if(!$stmt->execute()) return false;
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $stmt->close();
        
        if(!empty($rows)){
            return $rows;
        }else{
            return false;
        }
        
    }
    public static function insert($query,$params=[],$types = null)
    {
        $stmt = self::$conn->prepare($query);
        if(isset($types)){
            if(isset($types,$params))$stmt->bind_param($types, ...$params);
        }else{
            $types=self::paramTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $insertId = $stmt->insert_id;
        $stmt->close();
        return $insertId;
    }
    public static function execute($query, $params = null,$types = null)
    {
        $stmt = self::$conn->prepare($query);
        if(isset($types,$params)){
            if(isset($types,$params))$stmt->bind_param($types, ...$params);
        }else if(isset($params)){
            $types=self::paramTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        if($stmt->execute()) return true;
        return false;
    }
    protected static function paramTypes($param){
        $types="";
        foreach($param as $p){
            switch(gettype($p)){
                case "string":
                    $types.="s";
                    break;
                case 'integer':
                    $types.="i";
                    break;
                case 'double':
                    $types.="d";
                    break;
                case 'BLOB':
                    $types.="b";
                    break;
            }
        }
        return $types;
    }
}
//sanitize
class sn{
    //tested
    public static function test_input( $data ) {
        $data = trim( $data );
        $data = stripslashes( $data );
        $data = strip_tags( $data );
        return $data;
    }
    //tested
    public static function isSpace($str){
        return ctype_space($str);
    }
    //tested
    public static function onlyNumbers($str){
        if (preg_match("/^\d+$/", $str)) {
            return true;
        } else {
            return false;
        }
    }
    //tested
    public static function onlyLetters($str){
        if (preg_match("/^[a-zA-Z' ]*$/",$str)) {
            return true;
          }
          
    }
    //tested
    public static function hasSymbols($str){
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/",$str)) {
            return true;
          }
          
    }
    public static function hasSpace($str){
        if(str_contains($str, ' ')){
            return true;
        }
    }

    public static function isEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
       }
    }
    public static function empty(...$str){
        $strArr=[...$str];
        $state=false;
        foreach($strArr as $str){
            if (!isset($str) or $str == "" or ctype_space($str)) {//for strings
                $state=true;
                break;
            }
        }
        return $state;
    }
}
class formats{
    public static function date($datetime){
        $datetime = strtotime($datetime);
        $day = date("d", $datetime);
        $month=date("F",$datetime);
        $year=date("Y",$datetime);
        $date=[
            "day"=>$day,
            "month"=>$month,
            "year"=>$year,
        ];
        return $date;
    }

}
//functions
class fns{
    public static function readingTime($str){
        $string=strip_tags($str);
        $wordsPerMinute = 300;//average case
        $words = count(explode(" ",$string));
        if($words >0){
            $time = ceil($words / $wordsPerMinute);
            return $time;
        }else{
            return 0;
        }
    }
}
//route
//tested
class Route{
    public static $base="";
    public static $data=[];
    protected static $routes=[];
    public static function render($name,$content=null){
        if(isset($content)){
            route::$data[$name]=$content;
        }else{
            if(isset(route::$data[$name])) return route::$data[$name];
        }
    }
    public static function match($url,$pathname=null){
        $pathname=strtok($_SERVER["REQUEST_URI"], '?');
        $param = self::setparam($url, $pathname);
            if ($url == $pathname) {
                return true;
    
            } else if (count($param)!= 0) {
                {
                 return true; 
                }
            }else{
                return false;
            }
    }
    protected static function similiarArr($arr1,$arr2){
        if(count(array_filter($arr1,'strlen'))==count(array_filter($arr2,'strlen'))){
            return true;
        }
        return false;
    }
    protected static function setparam ($drl, $url) {
        $r = [];
        $keys=explode("/",$drl);
        $values=explode("/",$url);
        // echo '<pre>';
        // print_r($keys);
        $pair=[];
        $match=false;
        if(self::similiarArr($keys,$values)){
            for($i=0;$i<count($keys);$i++){
                // if key and values are not equal,then they must be dynamic values
                if($keys[$i]!=$values[$i]){
                    if(preg_match('/{|}/',$keys[$i])) //if dynamic values
                    { $pair[preg_replace('/{|}/',"",$keys[$i])]=$values[$i];  
                        
                    }else{
                        //if not dynamic values
                        return $pair;
                    }
                }
                //echo $match;
            }
        }
        //print_r($pair);
        return $pair;
    }
    public static function get($url,$callback){
        $pathname=strtok($_SERVER["REQUEST_URI"], '?');
        $url=self::$base.$url;
        array_push(self::$routes,$url);
        if($url==self::$base."*"){
            $routes = self::$routes;
            $results = [];
                for($i=0;$i<count($routes);$i++){
                    array_push($results,self::match($routes[$i]));
                }
                $results=array_unique($results);
                if(!in_array(true, $results)){
                    $callback();
                }
                return;
        }else{
        
        $param=self::setparam($url,$pathname);
        if(self::match($url)){
            $callback($param);
        }
        }
    }
    public static function request($agrs){
        $url = $agrs['url'];
        $ch = curl_init();
        $data="";
        $success=$agrs['success'];
        $fail=$agrs['fail'];
        if(isset($agrs['data'])){
            $data=http_build_query($agrs['data']);
        }
        // Set query data here with the URL
        curl_setopt($ch, CURLOPT_URL, $url."?".$data); 
        if(isset($agrs['method']) and $agrs['method']=="POST"){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = trim(curl_exec($ch));
        if(curl_errno($ch)){
            $fail('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
        $success($content);
    }
    protected static function fileToVar($file){
        ob_start();
        require($file);
        return ob_get_clean();
    }
    public static function view($name,$values=[]){
        if(gettype($name)!="string"){
            exit("In method view argument 1 must be type of string");
        }
        if(gettype($values)!="array"){
            exit("In method view argument 2 must be type of array");
        }
        $newvalues=[];
        foreach($values as $key=>$value){
            array_push($newvalues,$newvalues['{{'.$key.'}}']=$value);
        }
        //print_r($newvalues);
        $view=self::fileToVar("views/".$name.".view.php");
        if(preg_match_all('/{{(.*?)}}/',$view,$matchs)){
            foreach($matchs[0] as $match){
                $pattern="/".$match."/";
                if(isset($newvalues[$match])){
                    $view= preg_replace($pattern,$newvalues[$match],$view);
                }
            }
        }
        return $view;
    }
}

?>