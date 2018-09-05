<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


class Operations {  
    
   var $connection;        
   var $action;
   
   function __construct(){
      /* Make connection to database */
     $this->connection =mysqli_connect("localhost", "root", "ctrls@123", "schooln");
     $action= strtolower(trim(mysqli_real_escape_string($this->connection,$_REQUEST['action'])));
         
      switch($action){
            case "login" : $this->login();
                        break;
            case "users_list" : $this->fetch_all_users();
                  break;
             case "inst_lists" : $this->fetch_institutes();
                  break;
            default : echo '{"result":"Invalid"}';
        }
   }
   
   function login(){
        $username =strtolower(trim(mysqli_real_escape_string($this->connection,$_REQUEST['username'])));
        $password =strtolower(trim(mysqli_real_escape_string($this->connection,$_REQUEST['password'])));
        $result="sucess";$err_reason="";
        if(strlen($username)==0){
            $result="error";
            $err_reason="**Invalid Username";
        }elseif(strlen($password)==0){
            $result="error";
            $err_reason="**Invalid Password";
        }else{
           $sql= "SELECT * FROM `users`   WHERE `username` = '".$username."' and `password` = '".$password."' and `status`=1  AND level >76 ";
           
           $sql=  mysqli_query($this->connection, $sql);
            $userdata="";
            if(mysqli_num_rows($sql)==0){
                $result="error";
                $err_reason="**Invalid Login Credentials";
            }else{
            $sql=  mysqli_fetch_array($sql);
            $level=$sql['level'];
            switch($level){
              case 99 : $level="Admin";
                                    break;  
              case 77 : $level="Reseller";
                    break;  
                default :$level=" "; break;
            }
            $userdata='"Userdata" :{"username": "'.$sql['username'].'","password": "'.$sql['password'].'","level": "'.$level.'","organisation": "'.$sql['organisation'].'","mobile": "'.$sql['mobile'].'" }';
            }
            if(strlen($userdata)!=0){
                echo '{"result":"'.$result.'",'.$userdata.' }';
            }else{
                 echo '{"result":"'.$result.'","error":"'.$err_reason.'" }';
            }
            
        }
    }
    
   
   function fetch_all_users(){
       
       $username =strtolower(trim(mysqli_real_escape_string($this->connection,$_REQUEST['username'])));
       $password =strtolower(trim(mysqli_real_escape_string($this->connection,$_REQUEST['password'])));
      $result="sucess";$err_reason="";
        if(strlen($username)==0){
            $result="error";
            $err_reason="**Invalid Username";
        }elseif(strlen($password)==0){
            $result="error";
            $err_reason="**Invalid Password";
        }else{
            $user_data = $this->check_user($username, $password);
            
            if(!$user_data){
                $err_reason="**Invalid Login Credentials";
            }else{
                if($user_data['level']==99){
                      $users =mysqli_query($this->connection,"SELECT u.*,m.username as master FROM `users` u  JOIN users m ON u.master=m.id WHERE u.level <=77 ");
                }else{
                      $users =mysqli_query($this->connection,"SELECT u.*,m.username as master FROM `users` u  JOIN users m ON u.master=m.id where u.level <=77 AND u.master='".$user_data['id']."' ");
                }
            
               $user_json='"Users_list": [';
               $users_count=0;
              while ($row =  mysqli_fetch_assoc($users) ) {
                  $users_count++;
                  $level=$row['level'];
                    switch($level){
                    case 99 : $level="Admin";
                                          break;  
                    case 77 : $level="Reseller";
                          break;  
                    case 55 : $level="Institute Master";
                          break;  
                    case 22 : $level="Support";
                          break;  
                      default :$level=" "; break;
                  }
                  $status="Active";
                  if($row['status']!=1){
                      $status="De-Activated";
                  }
                  $user_json.='{ "name":"'.$row['username'].'","level":"'.$level.'","status":"'.$status.'","master":"'.$row['master'].'", "mobile":"'.$row['mobile'].'","organisation":"'.$row['organisation'].'"},';
              }
              
              $user_json=  substr($user_json, 0, strlen($user_json)-1);
              $user_json.="]";
              if(strlen($err_reason)==0){
                    echo '{"result":"'.$result.'",'.$user_json.' ,"users_count" :"'.$users_count.'"}';
                }else{
                     echo '{"result":"'.$result.'","error":"'.$err_reason.'" }';
                }
              
            }            
        }       
   }   
   
   
   function fetch_institutes(){
       
       $username =strtolower(trim(mysqli_real_escape_string($this->connection,$_REQUEST['username'])));
       $password =strtolower(trim(mysqli_real_escape_string($this->connection,$_REQUEST['password'])));
       $result="sucess";$err_reason="";
        if(strlen($username)==0){
            $result="error";
            $err_reason="**Invalid Username";
        }elseif(strlen($password)==0){
            $result="error";
            $err_reason="**Invalid Password";
        }else{
            $user_data = $this->check_user($username, $password);
            
            if(!$user_data){
                $err_reason="**Invalid Login Credentials";
            }else{
                if($user_data['level']==99){
                      $users =mysqli_query($this->connection,"SELECT i.id,i.name,i.address,i.mobile,i.email,i.logo,i.status,u.username as master ,ic.code FROM `institutes` i JOIN users u ON i.master=u.id JOIN institute_code ic ON ic.iid=i.id  ");
                }else{
                      $users =mysqli_query($this->connection,"SELECT i.id,i.name,i.address,i.mobile,i.email,i.logo,i.status,u.username as master ,ic.code FROM `institutes` i JOIN users u ON i.master=u.id JOIN institute_code ic ON ic.iid=i.id WHERE i.master='".$user_data['id']."' ");
                }
            
               $user_json='"Institute_list": [';
               $users_count=0;
              while ($row =  mysqli_fetch_assoc($users) ) {
                  $users_count++;
                  $status="Activated";
                  if($row['status']!=1){
                      $status="De-Activated";
                  }
                 $img_url="http://ems.snetworkit.com/schooln/assests_2/uploads/".$row['logo'];
                 $row['address'] = urlencode($row['address']);
                
               //   print_r($row);
                  $user_json.='{ "id":"'.$row['id'].'","name":"'.$row['name'].'","code":"'.$row['code'].'","address":"'.$row['address'].'","status":"'.$status.'","master":"'.$row['master'].'","image":"'.$img_url.'", "mobile":"'.$row['mobile'].'","email":"'.$row['email'].'"},';
             
               
                  }
              
              $user_json=  substr($user_json, 0, strlen($user_json)-1);
              $user_json.="]";
              if(strlen($err_reason)==0){
                    echo '{"result":"'.$result.'",'.$user_json.' ,"inst_count" :"'.$users_count.'"}';
                }else{
                     echo '{"result":"'.$result.'","error":"'.$err_reason.'" }';
                }
              
            }            
        }       
   }  
   
   private function check_user($user,$pass){
        $sql= "SELECT * FROM `users` WHERE `username` = '".$user."' and `password` = '".$pass."' and `status`=1  AND level >76 ";
        $sql=  mysqli_query($this->connection, $sql);
        if(mysqli_num_rows($sql)==0){
            return false;
        }else{
               $sql=  mysqli_fetch_array($sql);
               return $sql;
        }
        
       
   }
    
}


$database = new Operations;

?>

