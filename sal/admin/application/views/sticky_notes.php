<?php

    if(isset($_REQUEST)){
        $notes =  $_REQUEST['sticky_notes'];
        $staff_id = $_REQUEST['staff_id'];

        $query ="REPLACE into `sticky_notes` (`staff_id`, `notes`, `status`) values($staff_id,'".$notes."', 1)";
        $query_res=$this->db->query($query);
        if($query_res){
            $responce['status'] = 'success';
        }else{
            $responce['status'] = 'error';
        }

        echo json_encode($responce);
    }

?>