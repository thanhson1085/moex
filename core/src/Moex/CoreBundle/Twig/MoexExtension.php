<?php
namespace Moex\CoreBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;

class MoexExtension extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            'cut' => new Twig_Filter_Method($this, 'filterCut', array('length' => false, $wordCut = false, 'appendix' => false)),
            'price' => new Twig_Filter_Method($this, 'priceFilter'),
        );
    }
    
    public function filterCut($text, $length = 160, $wordCut = true, $appendix = ' ...')
    {
        $maxLength = (int)$length - strlen($appendix);
        if (strlen($text) > $maxLength) {
            if($wordCut){
                $text = substr($text, 0, $maxLength + 1);
                $text = substr($text, 0, strrpos($text, ' '));
            }
            else {
                $text = substr($text, 0, $maxLength);
            }
            $text .= $appendix;
        }
        
        return $text;
    }

    public function getName()
    {
        return 'moex_extension';
    }
}
