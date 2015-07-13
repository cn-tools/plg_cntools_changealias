<?php
/**
 * plg_cntools_changealias - Joomla Plugin
 *
 * @package    Joomla
 * @subpackage Plugin
 * @author Clemens Neubauer
 * @link https://github.com/cn-tools/
 * @license		GNU/GPL, see LICENSE.php
 * plg_cntools_bannerext is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class PlgContentPlg_CNTools_ChangeAlias extends JPlugin
{
	public function PlgContentPlg_CNTools_ChangeAlias( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}
	
	//--------------------------------------------------------------
	public function onContentBeforeSave($context, $article, $isNew) 
	{
		if (is_object($article) and property_exists($article, 'alias'))
		{
			if (($isNew) || ($this->params->get('onlynew')=='0'))
			{
				$article->alias = $this->transferChars($article->alias);
			}
		}
		return true;
	}

	//--------------------------------------------------------------
	protected function transferChars($basestr)
	{
		//-- maybe transfer alias into upper/lower-case characters
		$doUpLow = $this->params->get('uplow');
		if ($doUpLow == '1') {
			$basestr = mb_strtoupper($basestr);
		} elseif ($doUpLow == '2') {
			$basestr = mb_strtolower($basestr);
		}
		
		//-- maybe transfer alias characters
		if ($this->params->get('values')){
			$lValues = array_map('trim', explode("\n", $this->params->get('values')));
			
			//-- rework every item for alias
			foreach ($lValues as &$lSubArray){
				$workArray = explode('=', $lSubArray); 
				if ($this->params->get('casesensitiv')){
					// use case sensitiv chars
					$basestr = str_replace($workArray[0] , $workArray[1], $basestr); 
				} else {
					// do not use case sensitive chars
					$basestr = str_ireplace($workArray[0] , $workArray[1], $basestr); 
				}
			}
		}
		return $basestr;
	}
}
?>
