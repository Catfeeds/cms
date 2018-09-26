<?php
/**
 * PayAction.class.php (支付模块)
 *
 */
if(!defined("ThinkPHP")) exit("Access Denied");
class PayAction extends BaseAction
{

	function _initialize()
    {	
		parent::_initialize();
		if(!$this->_userid){
			//$this->assign('jumpUrl',U('User/Login/index'));
			//$this->error(L('nologin'));
		}
		$this->dao = M('User');
		$this->assign('bcid',0);
		$user = $this->dao->find($this->_userid);
		$this->assign('vo',$user);
    }

    public function index()
    {
        $this->display();
    }


	public function Recharge()
    {
        $this->display();
    }
	

	public function pay()
    {
        $this->display();
    }
	public function respond()
	{
		$pay_code = !empty($_REQUEST['code']) ? get_safe_replace($_REQUEST['code']) : '';	
		$pay_code = ucfirst($pay_code);
		$Payment = M('Payment')->getByPayCode($pay_code);
		if(empty($Payment)){
			$this->erroryulia(L('PAY CODE EROOR!'));exit();
		}
		$aliapy_config = unserialize($Payment['pay_config']);		 
		import("@.Pay.".$pay_code);
		$pay=new $pay_code($aliapy_config);
		$r = $pay->respond();	
		$jumpUrl = U('User/Order/index');
		if($r){
			$this->erroryulia(L('PAY_OK'),$jumpUrl);exit();
		}else{
			$this->erroryulia(L('PAY_FAIL'),$jumpUrl);exit();
		}
	}
 
}
?>