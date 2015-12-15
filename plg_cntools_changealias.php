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

// import library dependencies
jimport('joomla.plugin.plugin'); 

class PlgContentPlg_CNTools_ChangeAlias extends JPlugin
{
	public function PlgContentPlg_CNTools_ChangeAlias( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}
	
	//-------------------------------------------------------------------------
	public function onContentBeforeSave($context, $article, $isNew) 
	{
		if (is_object($article) and property_exists($article, 'alias'))
		{
			if (($isNew) or ($this->params->get('onlynew')=='0'))
			{
				if ($this->checkContext($context))
				{
					$article->alias = $this->transferChars($article->alias);
				}
			}
		}
		return true;
	}

	//-------------------------------------------------------------------------
	protected function checkContext($context)
	{
		$lResult = true;
		if ($this->params->get('contextyesno', '0') == '1')
		{
			//-- include part ---------------------------------------------
			$lResult = $this->isContextFound($context);
		}
		elseif ($this->params->get('contextyesno', '0') == '2') 
		{
			//-- exclude part ---------------------------------------------
			$lResult = !$this->isContextFound($context);
		}
		
		return $lResult;
	}
	//-------------------------------------------------------------------------
	protected function isContextFound($context)
	{
		$lValues = array_map('trim', explode("\n", $this->params->get('context')));

		foreach ($lValues as $lValue){
			//-- check if lValue is in context ---------------------------------
			if (stripos($context, $lValue) !== false) {
				return true;
			}
		}

		return false;
	}
	//-------------------------------------------------------------------------
	protected function transferChars($alias)
	{
		//-- maybe transfer alias into upper/lower-case characters
		$doUpLow = $this->params->get('uplow');
		if ($doUpLow == '1') {
			$alias = mb_strtoupper($alias);
		} elseif ($doUpLow == '2') {
			$alias = mb_strtolower($alias);
		}
		
		//-- maybe transfer alias characters
		if ($this->params->get('values')){
			$lValues = array_map('trim', explode("\n", $this->params->get('values')));

			//-- rework every item for alias
			foreach ($lValues as &$lSubArray){
				$lSubArray = trim($lSubArray);
				$lSubArray = trim($lSubArray, '=');
				if (stripos($lSubArray, '=') !== false) {
					// special transfer '[space]' to one space ' '
					$lSubArray = str_ireplace('[space]' , ' ', $lSubArray);
					// special transfer '[nothing]' to empty string ''
					$lSubArray = str_ireplace('[nothing]' , '', $lSubArray);

					$workArray = explode('=', $lSubArray); 
					
					if ($this->params->get('casesensitiv')){
						// use case sensitiv chars
						$alias = str_replace($workArray[0] , $workArray[1], $alias); 
					} else {
						// do not use case sensitive chars
						$alias = str_ireplace($workArray[0] , $workArray[1], $alias); 
					}
				}
			}

			// Trim white spaces at beginning and end of alias
			$alias = trim($alias);
		}
		return $alias;
	}
}
?>
