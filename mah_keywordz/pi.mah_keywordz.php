<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Keywordz',
	'pi_version' => '2.1',
	'pi_author' => 'Micky Hulse',
	'pi_author_url' => 'http://hulse.me/',
	'pi_description' => 'Keywordz will take an input string and output comma-delimited (meta) keyword string. Common and duplicate words will be removed from the output string.',
	'pi_usage' => Mah_keywordz::usage()
);

/**
 * Mah_keywordz Class
 * 
 * @package      ExpressionEngine
 * @category     Plugin
 * @author       Micky Hulse
 * @copyright    Copyright (c) 2010-2011, Micky Hulse
 * @link         http://hulse.me/
 */

class Mah_keywordz
{
	
	var $return_data = '';
	var $class = 'Mah_keywordz'; // Used in usort() callback function.
	
	/**
	 * Constructor
	 *
	 * @access     public
	 * @return     void
	 */
	
	function Mah_keywordz($str = '')
	{
		
		// ----------------------------------
		// Call super object:
		// ----------------------------------
		
		$this->EE =& get_instance();
		
		// ----------------------------------
		// Passing data directly:
		// ----------------------------------
		
		$string = ($str == '') ? $this->EE->TMPL->tagdata : $str;
		
		// ----------------------------------
		// Get parameter(s):
		// ----------------------------------
		
		$string = ($str == '') ? $this->EE->TMPL->fetch_param('string') : $str;
		$string = ($string == '') ? $this->EE->TMPL->tagdata : $string;
		$sort = $this->EE->TMPL->fetch_param('sort', 'length');
		$order = $this->EE->TMPL->fetch_param('order', 'descending'); // strtolower
		$limit = $this->EE->TMPL->fetch_param('limit', 0); // intval
		$default = $this->EE->TMPL->fetch_param('default');
		$delimiter = $this->EE->TMPL->fetch_param('delimiter', ',');
		
		// ----------------------------------
		// Return:
		// ----------------------------------
		
		$this->return_data = (!$string) ? $default : $this->_make_keywordz($string, $sort, $order, $limit, $delimiter);
		
	}
	
	//--------------------------------------------------------------------------
	//
	// Private methods:
	//
	//--------------------------------------------------------------------------
	
	/**
	 * Make Keywordz
	 * 
	 * Returns string of keywords.
	 * 
	 * @access     private
	 * @param      string
	 * @param      string
	 * @param      string
	 * @param      integer
	 * @param      string
	 * @return     string
	 * @info:      http://snipplr.com/view/6353/php-remove-common-words/
	 */
	
	function _make_keywordz($string, $sort, $order, $limit, $delimiter)
	{
		
		// ----------------------------------
		// Sanatize:
		// ----------------------------------
		
		$string = trim($string);
		$string = addslashes($string);
		$string = strtolower($string);
		
		// ----------------------------------
		// Remove common words:
		// ----------------------------------
		
		$return = $this->_remove_common_words($string);
		
		// ----------------------------------
		// Remove duplicates:
		// ----------------------------------
		
		$return = array_unique($return);
		
		// ----------------------------------
		// Sorting?
		// ----------------------------------
		
		switch(strtolower($sort))
		{
			
			case 'alphabetical':
				
				usort($return, array($this->class, '_sort_by_alphabet'));
				break;
			
			default:
				
				usort($return, array($this->class, '_sort_by_key_length'));
			
		}
		
		// ----------------------------------
		// Reverse the array and reset key 
		// indexes, or just reset key indexes:
		// ----------------------------------
		
		$return = (strtolower($order) == 'ascending') ? array_reverse($return) : array_values($return);
		
		// ----------------------------------
		// Limit:
		// ----------------------------------
		
		if (intval($limit) > 0) $return = array_slice($return, 0, intval($limit));
		
		return implode($delimiter, $return);
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Remove Common Keywords
	 * 
	 * Removes common words from a string.
	 * 
	 * @access     private
	 * @param      string
	 * @return     string
	 * @info:      http://snipplr.com/view/6353/php-remove-common-words/
	 */
	
	function _remove_common_words($string)
	{
		
		$common = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
		
		preg_match_all('/([a-zA-Z]{3,})/', $string, $matches);
		
		return array_diff($matches[0], $common);
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Sort by Key Length
	 * 
	 * Sorts an array by key value length.
	 * 
	 * @access     private
	 * @param      string
	 * @param      string
	 * @return     integer
	 * @info: 
	 *        To use in a class: usort($myArray, array("className", "cmp"));
	 *        http://us3.php.net/usort
	 *        http://stackoverflow.com/questions/838227/php-sort-an-array-by-the-length-of-its-values
	 */
	
	function _sort_by_key_length($a, $b)
	{
		
		return strlen($b) - strlen($a);
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Sort by Alphabet
	 * 
	 * Sorts an array in alphabetical order.
	 * 
	 * @access     public
	 * @param      string
	 * @param      string
	 * @return     integer
	 */
	
	function _sort_by_alphabet($a, $b)
	{
		
		if (ord(substr($a, 0, 1)) == ord(substr($b, 0, 1))) return 0;
		return (ord(substr($a, 0, 1)) < ord(substr($b, 0, 1))) ? -1 : 1;
		
	}
	
	//--------------------------------------------------------------------------
	//
	// Usage:
	//
	//--------------------------------------------------------------------------
	
	/**
	 * Usage
	 *
	 * Plugin Usage
	 *
	 * @access     public
	 * @return     string
	 */
	
	function usage()
	{
		
		ob_start();
		
		?>
		
		More information & documentation:
		
		https://github.com/mhulse/mah_keywordz
		
		<?php
		
		$buffer = ob_get_contents();
		
		ob_end_clean(); 
		
		return $buffer;
		
	}
	
	// --------------------------------------------------------------------
	
}

/* End of file pi.mah_keywordz.php */
/* Location: ./system/expressionengine/mah_keywordz/pi.mah_keywordz.php */