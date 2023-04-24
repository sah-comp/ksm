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
 * File model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_File extends Model
{
    /**
     * Returns an array with attributes for lists.
     *
     * @param string (optional) $layout
     * @return array
     */
    public function getAttributes($layout = 'table')
    {
        return [
            [
                'name' => 'name',
                'sort' => [
                    'name' => 'name'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'value',
                'sort' => [
                    'name' => 'value'
                ],
                'callback' => [
                    'name' => 'decimal'
                ],
                'class' => 'number',
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ]
        ];
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
    }

    /**
     * Read a directory and return a unordered html list.
     *
     * @see https://stackoverflow.com/questions/10779546/recursiveiteratoriterator-and-recursivedirectoryiterator-to-nested-html-lists/10780023#10780023
     *
     * @param string $path path to a certain directory
     * @return string
     */
    public function dir($path = '/'):string
    {
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        $dom = new DomDocument("1.0");
        $list = $dom->createElement("ul");
        $dom->appendChild($list);
        $node = $list;
        $depth = 0;
        foreach ($objects as $name => $object) {
            if ($objects->getDepth() == $depth) {
        //the depth hasnt changed so just add another li
                //$li = $dom->createElement('li', $this->buildFileLink($object));
                $li = $this->createListItem($dom, $object);
                $node->appendChild($li);
            } elseif ($objects->getDepth() > $depth) {
        //the depth increased, the last li is a non-empty folder
                $li = $node->lastChild;
                $ul = $dom->createElement('ul');
                $li->appendChild($ul);
                //$ul->appendChild($dom->createElement('li', $this->buildFileLink($object)));
                $li = $this->createListItem($dom, $object);
                $ul->appendChild($li);
                $node = $ul;
            } else {
        //the depth decreased, going up $difference directories
                $difference = $depth - $objects->getDepth();
                for ($i = 0; $i < $difference; $difference--) {
                    $node = $node->parentNode->parentNode;
                }
                $li = $this->createListItem($dom, $object);
                $node->appendChild($li);
            }
            $depth = $objects->getDepth();
        }
        return $dom->saveHtml();
    }

    /**
     * Makes a a href link for the file object.
     *
     * @param $object
     * @return mixed
     */
    public function createListItem(&$dom, $file)
    {
        $e = $dom->createElement('a', $file->getFilename());
        $a = $dom->appendChild($e);
        $a->setAttribute('href', 'file:'.$file->getPathname());

        $li = $dom->createElement('li');
        $li->appendChild($e);
        return $li;
    }
}
