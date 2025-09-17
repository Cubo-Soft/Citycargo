<?php 
class dashboardController{

    public function dashboard(){
        require_once('views/components/layout/head.php');
        require_once('views/dashboard/dashboard.php');
        require_once('views/components/layout/scripts.php');
    }

}
