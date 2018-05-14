<?php 
    class deleteorderstabController extends ModuleAdminController {
        public function __construct() {
          parent::__construct();
        }


        public function renderList(){
            //include(_PS_MODULE_DIR_.'deleteordersfree/deleteordersfree.php'); 
            $this->deleteorders = new deleteordersfree();
            //$this->deleteorders->displayAdvert();
            $msg="";
    		if (isset($_POST['idord'])){
    			if (is_numeric($_POST['idord'])){
    				$msg=$this->deleteorders->deleteorderbyid($_POST['idord'],1);
    			}
    		}
            
    		//$this->deleteorders->token = $this->token;
    		//echo $this->deleteorders->displayinputid();
    		
            return $msg.$this->deleteorders->displayAdvert(1).$this->deleteorders->displayinputid(1).$this->deleteorders->displayFooter(1);
        }
    }
?>