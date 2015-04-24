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

	public function onContentBeforeSave($context, $article, $isNew) 
	{
		if (is_object($article))
		{
			if (($isNew) || ($this->params->get('onlynew')=='0'))
			{
				$article->alias = $this->transferChars($article->alias);
			}
		}
		return true;
	}

	protected function transferChars($str)
	{
		$str = str_replace(array('ä', 'ö', 'ü', 'ß', 'Ä', 'Ö', 'Ü'), array('ae', 'oe', 'ue', 'ss', 'AE', 'OE', 'UE'), $str); 
		return $str;
	}
}
?>
