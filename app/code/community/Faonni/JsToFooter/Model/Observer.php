<?php
/**
 * Faonni
 *  
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade module to newer
 * versions in the future.
 * 
 * @package     Faonni_JsToFooter
 * @copyright   Copyright (c) 2014 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<?php
class Faonni_JsToFooter_Model_Observer 
{
    /**
     * Move js to footer
     *
     * @param $observer Varien_Event_Observer
     * @return Faonni_JsToFooter_Model_Observer
     */	
    public function move(Varien_Event_Observer $observer) 
	{
        if (!Mage::getStoreConfig('dev/js/move_to_footer', Mage::app()->getStore()->getId())){
			return $this;
		}
		
		$block = $observer->getEvent()->getBlock();		
		if ('root' != $block->getNameInLayout()) return false;
		
        $transport = $observer->getEvent()->getTransport();		
        $html = $transport->getHtml();		
		$dom = new DOMDocument();
		$dom->loadHTML($html);
		
		$body = $dom->getElementsByTagName('body')->item(0);
		
		if ($body instanceof DOMNode){		
			$scripts = $dom->getElementsByTagName('script');
			if ($scripts instanceof DOMNodeList){
				$remove = array();
				foreach($scripts as $script){
				  $remove[] = $script;
				}

				foreach ($remove as $script){
				  $script->parentNode->removeChild($script); 
				}
			
				foreach ($remove as $script){
					$body->appendChild($script);
				}
				// we want a nice output
				$dom->formatOutput = true;	
				$dom->preserveWhiteSpace = false;	
				$html = $dom->saveHTML();
				$transport->setHtml($html);
			}
		}
		return $this;
    }
}