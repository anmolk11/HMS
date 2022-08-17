<?php

namespace dbPlayer;


class dbPlayer {

    private $db_host="localhost";
    private $db_name="hms";
    private $db_user="root";
    private $db_pass="";
    protected $conn;

   public function open(){
        $host = "127.0.0.1";
        $username = "root";
        $pass = "foobar";
        $this->conn = mysqli_connect($this->db_host,$this->db_user,$this->db_pass,$this->db_name);
        // Check connection
        if (!$this->conn) {
          //die("Connection failed: " . mysqli_connect_error());
          return mysqli_connect_error();
        }
        return "true";

    }
    public  function close()
    {
        $res=mysqli_close($this->conn);
        if($res)
        {
            return "true";
        }
        else
        {
            return mysqli_connect_error();
        }

    }

    public function insertData($table,$data)
    {
        $keys   = "`" . implode("`, `", array_keys($data)) . "`";
        $values = "'" . implode("', '", $data) . "'";
       //var_dump("INSERT INTO `{$table}` ({$keys}) VALUES ({$values})");
        mysqli_query($this->conn,"INSERT INTO `{$table}` ({$keys}) VALUES ({$values})");

        return mysqli_insert_id($this->conn);
        //mysqli_connect_error();

    }
    public function registration($query,$query2)
    {
        $res=mysqli_query($this->conn,$query);
        if($res)
        {

            $res=mysqli_query($this->conn,$query2);
            if($res)
            {

                return "true";
            }
            else
            {
               return mysqli_connect_error();

            }

        }
        else
        {
            return mysqli_connect_error();
        }


    }
    public  function  getData($query)
    {
        $sql = mysqli_query($this->conn, $query);
        return $sql;
        /*if(!$sql)
        {
            return "Can't get data ".mysqli_connect_error();
        }
        else
        {
            return $sql;
        }*/

    }
    public function  update($query)
    {
        if ($res = mysqli_query($this->conn,$query))
        {
            return "true";    
        }
        return "Can't update data ".mysqli_connect_error();
    }
    public  function  updateData($table,$conColumn,$conValue,$data)
    {
        $updates=array();
        if (count($data) > 0) {
            foreach ($data as $key => $value) {

                $value = mysqli_real_escape_string($this->conn,$value); // this is dedicated to @Jon
                $value = "'$value'";
                $updates[] = "$key = $value";
            }
        }
        $implodeArray = implode(', ', $updates);
        $query ="UPDATE ".$table." SET ".$implodeArray." WHERE ".$conColumn."='".$conValue."'";
       //var_dump($query);
        if ($res = mysqli_query($this->conn,$query))
        {
            return "true";    
        }
        return "Can't update data ".mysqli_connect_error();
    }

    public  function delete($query)
    {
        if ($res = mysqli_query($this->conn,$query))
        {
            return "true";    
        }
        return "Can't delete data ".mysqli_connect_error();
    }

    public  function  getAutoId($prefix)
    {
        $uId="";
        $q = "select number from auto_id where prefix='".$prefix."';";
        $result = $this->getData($q);
        $userId=array();
        while($row = mysqli_fetch_assoc($result))
        {

            array_push($userId,$row['number']);

        }
        // var_dump($UserId);
        if(strlen($userId[0])>=1)
        {
            $uId=$prefix."00".$userId[0];
        }
        elseif(strlen($userId[0])==2)
        {
            $uId=$prefix."0".$userId[0];
        }
        else
        {
            $uId=$prefix.$userId[0];
        }
        array_push($userId,$uId);
        return $userId;

    }
    public  function  updateAutoId($value,$prefix)
    {
         $id =intval($value)+1;

        $query="UPDATE auto_id set number=".$id." where prefix='".$prefix."';";
        return $this->update($query);

    }

    public  function execNonQuery($query)
    {
        if ($res = mysqli_query($this->conn,$query))
        {
            //return "true";
            return $res;  
        }
        return "Can't Execute Query ".mysqli_connect_error();
    }
    public  function execDataTable($query)
    {
        if ($res = mysqli_query($this->conn,$query))
        {
            //return "true";    
            return $res;
        }
        return "Can't Execute Query ".mysqli_connect_error();
    }

}
