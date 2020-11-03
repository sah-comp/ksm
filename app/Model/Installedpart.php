<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Model
 * @author $Author$
 * @version $Id$
 */

/**
 * Installedpart model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Installedpart extends Model
{
    /**
     * Returns a article bean, either empty or the related one.
     *
     * @return RedBeanPHP\OODBBean
     */
    public function getArticle()
    {
        if (!$this->bean->article) {
            $this->bean->article = R::dispense('article');
        }
        return $this->bean->article;
    }

    /**
     * Returns a string combined from article number, name and original info.
     *
     * @return string
     */
    public function getConclusion()
    {
        $article = $this->bean->getArticle();
        $stack = [];
        $stack[] = $article->number;
        $stack[] = $article->description;
        /*
        if ($article->isoriginal) {
            $stack[] = I18n::__('article_literal_original');
        }
        */
        return trim(implode(' ', $stack));
    }

    /**
     * Returns wether the article is an original or not.
     *
     * @return string
     */
    public function isOriginal()
    {
        if ($this->getArticle()->isoriginal) {
            return I18n::__('article_literal_original');
        }
        return '';
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->stamp = date('Y-m-d', time());
        $this->addConverter('purchaseprice', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
        $this->addConverter('stamp', new Converter_Mysqldate());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
