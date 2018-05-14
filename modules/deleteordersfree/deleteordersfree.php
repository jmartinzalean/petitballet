<?php
class deleteordersfree extends Module {
	function __construct(){
		$this->name = 'deleteordersfree';
        $this->author = 'MyPresta.eu';
		$this->tab = 'Other';
		$this->version = '1.4.1';
        $this->module_key = '';
        $this->dir = '/modules/deleteordersfree/';
		parent::__construct();
		$this->displayName = $this->l('Delete Orders Free');
		$this->description = $this->l('Delete Orders Free is the best module for deleting orders. This version is free. Developed by MyPresta.eu');
		$this->tab = 'Admin';
        $this->tabClassName = 'deleteorderstab';
        $this->tabParentName = 'AdminOrders';
	}

	public function viewAccess($disable = false){
	        $result = true;
	        return $result;
	}

	function install(){
		if (parent::install() == false){
            return false;
        }
    	if (!isset($id_tab)) {
	      	$tab = new Tab();
            if ($this->psversion()==5 || $this->psversion()==6){
	      	    $tab->class_name = $this->tabClassName;
            } else {
                $tab->class_name = $this->tabClassName."14";
            }
	      	$tab->id_parent = Tab::getIdFromClassName($this->tabParentName);
	      	$tab->module = $this->name;
	      	$languages = Language::getLanguages();
	      	foreach ($languages as $language)
		        $tab->name[$language['id_lang']] = $this->displayName;
	    	$tab->add();
    	}
        return true;
	}

	public function uninstall(){
		if (parent::uninstall() == false) {
			return false;
		}
        if ($this->psversion()==5 || $this->psversion()==6){
    	    $id_tab = Tab::getIdFromClassName($this->tabClassName);
        } else {
            $id_tab = Tab::getIdFromClassName($this->tabClassName."14");
        }
	    if ($id_tab) {
	      $tab = new Tab($id_tab);
	      $tab->delete();
	    }
	    return true;
	}

	public function psversion() {
		$version=_PS_VERSION_;
		$exp=$explode=explode(".",$version);
		return $exp[1];
	}

	public function displayAdvert($return=0){
    $ret='<iframe src="http://mypresta.eu/content/uploads/2012/09/deleteorders_advertise.html" width="100%" height="130" border="0" style="border:none;"></iframe>';
        if ($return==0){
            echo $ret;
        } else {
            return $ret;
        }
	}



	public function displayFooter($return=0){
		$ret = "<div style=\"display:block; text-align:right; margin-top:5px;\">";
		$ret.= $this->l('proudly developed by')." <a style=\"font-weight:bold; color:orange;\" href=\"http://mypresta.eu\" target=\"_blank\">MyPresta.eu</a><br/><br/>";
        $ret.= '<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fmypresta&amp;width=200&amp;height=62&amp;show_faces=false&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=false&amp;appId=211918662219581" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:62px;" allowTransparency="true"></iframe>';
		$ret.= "</div>";
        if ($return==0){
            echo $ret;
        } else {
            return $ret;
        }
	}


	public function displayinputid($return=0){
	   if ($this->psversion()==5 || $this->psversion()==6){
	       $verps="";
	   } else {
	       $verps="14";
	   }
		$ret= "
		<script>
			function deleteorderbyid(id,msg){
				var answer = confirm(msg);
					if (answer){
						document.getElementById(\"deletebyid\").submit();
					}
			}			
		</script>
		
		<fieldset>
		<h3>".$this->l('Fill form with correct ORDER ID and delete it')."</h3>
			<div align=\"center\" style=\"margin-bottom:20px;\">
				<form action=\"index.php?tab=deleteorderstab$verps&token={$_GET['token']}\" method=\"post\" id=\"deletebyid\" name=\"deletebyid\">
				<strong>".$this->l('ORDER ID')."<br/></strong>
				<input style=\"margin-top:5px; text-align:center;\" type=\"text\" value=\"\" name=\"idord\" id=\"idord\"><br/><br/>
				<img src=\"../modules/deleteordersfree/delete.png\" onClick=\"deleteorderbyid(document.getElementById('idord'),'".$this->l('Are you sure you want to delete:')." #"."'+document.getElementById('idord').value+'"." ".$this->l('order?')."');\" style=\"cursor:pointer;\" ></form>
			</div>
		</fieldset>
        
        ";

        if ($return==0){
            echo $ret;
        } else {
            return $ret;
        }
	}

	public function deleteorderbyid($id,$return=0){
		$psversion=$this->psversion();

		if ($psversion==5 || $psversion==6){
			$thisorder = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('SELECT id_cart FROM '._DB_PREFIX_.'orders WHERE id_order = '.$id);

			if (isset($thisorder[0])){
				//deleting order_return
				$q= 'DELETE a,b FROM '._DB_PREFIX_.'order_return AS a LEFT JOIN '._DB_PREFIX_.'order_return_detail AS b ON a.id_order_return = b.id_order_return WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				//deleting order_slip
				$q= 'DELETE a,b FROM '._DB_PREFIX_.'order_slip AS a LEFT JOIN '._DB_PREFIX_.'order_slip_detail AS b ON a.id_order_slip = b.id_order_slip WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'cart_product WHERE id_cart="'.$thisorder[0]['id_cart'].'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'order_history WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'order_detail WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'orders WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}


				if (empty($this->errorlog)){
					$ret= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Order deleted').'" />'.$this->l('Order deleted').'</div>';
				} else {
					$ret= '<div class="warn error"><img src="../img/admin/warning.gif" alt="'.$this->l('Something wrong').'" />'.$this->l('Something wrong').'</div>';
				}

			} else {
					$ret= '<div class="warn error"><img src="../img/admin/warning.gif" alt="'.$this->l('Order with this id doesnt exists').'" />'.$this->l('Order with this id doesnt exists').'</div>';
			}
		}

		 if ($psversion==3){
			$thisorder = Db::getInstance()->ExecuteS('SELECT id_cart FROM '._DB_PREFIX_.'orders WHERE id_order = '.$id);

			if (isset($thisorder[0])){
				$q= 'DELETE a,b FROM '._DB_PREFIX_.'order_return AS a LEFT JOIN '._DB_PREFIX_.'order_return_detail AS b ON a.id_order_return = b.id_order_return WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q= 'DELETE a,b FROM '._DB_PREFIX_.'order_slip AS a LEFT JOIN '._DB_PREFIX_.'order_slip_detail AS b ON a.id_order_slip = b.id_order_slip WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'cart_discount WHERE id_cart="'.$thisorder[0]['id_cart'].'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'cart_product WHERE id_cart="'.$thisorder[0]['id_cart'].'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'order_history WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'order_discount WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'order_detail WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'orders WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				if (empty($this->errorlog)){
					$ret= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Order deleted').'" />'.$this->l('Order deleted').'</div>';
				} else {
					$ret= '<div class="warn error"><img src="../img/admin/warning.gif" alt="'.$this->l('Something wrong').'" />'.$this->l('Something wrong').'</div>';
				}

			} else {
					$ret= '<div class="warn error"><img src="../img/admin/warning.gif" alt="'.$this->l('Order with this id doesnt exists').'" />'.$this->l('Order with this id doesnt exists').'</div>';
			}
		 }

		if ($psversion==4){
			$thisorder = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('SELECT id_cart FROM '._DB_PREFIX_.'orders WHERE id_order = '.$id);

			if (isset($thisorder[0])){
				$q= 'DELETE a,b FROM '._DB_PREFIX_.'order_return AS a LEFT JOIN '._DB_PREFIX_.'order_return_detail AS b ON a.id_order_return = b.id_order_return WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q= 'DELETE a,b FROM '._DB_PREFIX_.'order_slip AS a LEFT JOIN '._DB_PREFIX_.'order_slip_detail AS b ON a.id_order_slip = b.id_order_slip WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'cart_discount WHERE id_cart="'.$thisorder[0]['id_cart'].'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'cart_product WHERE id_cart="'.$thisorder[0]['id_cart'].'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'order_history WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'order_discount WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'order_detail WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				$q = 'DELETE FROM '._DB_PREFIX_.'orders WHERE id_order="'.$id.'"';
				if(!Db::getInstance()->Execute($q)){$this->errorlog[] = $this->l("ERROR");}

				if (empty($this->errorlog)){
					$ret= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Order deleted').'" />'.$this->l('Order deleted').'</div>';
				} else {
					$ret= '<div class="warn error"><img src="../img/admin/warning.gif" alt="'.$this->l('Something wrong').'" />'.$this->l('Something wrong').'</div>';
				}

			} else {
				$ret= '<div class="warn error"><img src="../img/admin/warning.gif" alt="'.$this->l('Order with this id doesnt exists').'" />'.$this->l('Order with this id doesnt exists').'</div>';
			}
		}

        if ($return==0){
            echo $ret;
        } else {
            return $ret;
        }
	}


	public function displayForm(){
		return '';
	}

	public function getorders_psv3($limit = NULL){
		global $cookie;

		return Db::getInstance()->ExecuteS('
			SELECT *, (
				SELECT `name`
				FROM `'._DB_PREFIX_.'order_history` oh
				LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (osl.`id_order_state` = oh.`id_order_state`)
				WHERE oh.`id_order` = o.`id_order`
				AND osl.`id_lang` = '.(int)$cookie->id_lang.'
				ORDER BY oh.`date_add` DESC
				LIMIT 1
			) AS `state_name`
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = o.`id_customer`)
			ORDER BY o.`date_add` DESC
			'.((int)$limit ? 'LIMIT 0, '.(int)$limit : ''));
	}

}
?>