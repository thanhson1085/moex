<?php

namespace Moex\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MoexCoreBundle extends Bundle
{
	function __construct(){
		if(get_current_user_id() == 0) die('Permission denied!!!');
	}
}
