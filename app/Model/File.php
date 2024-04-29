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
     * Holds the file types which will be prefixed with a special prefix to open directly in a desktop app.
     *
     * @var array
     */
    public $filetypes = [
        'xls'  => [
            'prefix' => "ms-excel:ofe%7Cu%7C",
        ],
        'xlsx' => [
            'prefix' => "ms-excel:ofe%7Cu%7C",
        ],
        'doc'  => [
            'prefix' => "ms-word:ofe%7Cu%7C",
        ],
        'docx' => [
            'prefix' => "ms-word:ofe%7Cu%7C",
        ],
        'pdf'  => [
            'prefix' => "",
        ],
    ];

    /**
     * Holds files that will be ignored.
     *
     * @var array
     */
    public $ignore = [
        '.DS_Store',
        '.DAV',
    ];

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
                'name'   => 'name',
                'sort'   => [
                    'name' => 'name',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
            ],
            [
                'name'     => 'value',
                'sort'     => [
                    'name' => 'value',
                ],
                'callback' => [
                    'name' => 'decimal',
                ],
                'class'    => 'number',
                'filter'   => [
                    'tag' => 'number',
                ],
                'width'    => '8rem',
            ],
        ];
    }

    /**
     * Read a directory and output as a unorderer list.
     *
     * @param string $dir the path to the directory to scan
     */
    public function listFiles($dir)
    {
        clearstatcache();
        $files = scandir($dir);

        echo '<ul class="fileviewer">';
        foreach ($files as $file) {
            if (in_array($file, $this->ignore)) {
                continue;
            }
            if ($file != '.' && $file != '..') {
                $path = $dir . '/' . $file;

                // lookup the file. If none is found, dispense a empty one
                if ( ! $filebean = R::findOne('file', " ident = ? LIMIT 1", [md5($path)])) {
                    $filebean = R::dispense('file');
                }
                $filebean->path     = $path;
                $filebean->filename = $file;
                $filebean->size     = filesize($path);
                //$filebean->filemtime = filemtime($path);
                $filebean->filemtime = date("Y-m-d H:i:s", filemtime($path));
                R::store($filebean);

                echo '<li class="" id="file-' . $filebean->id . '">';

                // Check if it's a directory or a file
                if (is_dir($path)) {
                    echo '<details open>';
                    echo '<summary class="filer-folder" data-path="' . $path . '">';
                    echo $file;
                    echo '</summary>';
                    $this->listFiles($path);
                    echo '</details>';
                } else {
                    /*
                    $path_info = pathinfo($path);
                    $extension = $path_info['extension'];
                    $href = $path;
                    if (array_key_exists($extension, $this->filetypes)) {
                        $bridge = $this->filetypes[$extension];
                        $href = $bridge['prefix'] . WEBDAV_PREFIX . '/' . $file;
                    } else {
                        // which URL in case the file is not openable?
                    }
                    */
                    //$inspector_url = Url::build('/filer/inspector/%s', [$filebean->ident]);

                    ob_start();
                    Flight::render('filer/item', [
                        'record' => $filebean,
                        'href'   => $filebean->getHref(),
                    ]);
                    $html = ob_get_clean();
                    echo $html;

                    //echo '<a data-ident="' . $filebean->ident . '" class="inspector" data-intrinsic="' . $href . '" href="' . $inspector_url . '" title="' . I18n::__('scaffold_action_edit') . '">' . $file . '</a>';
                    //echo '<a href="' . $href . '" data-filename="' . $file . '">' . $file . '</a>';
                }

                echo '</li>';
            }
        }
        echo '</ul>';
    }

    /**
     * Read a directory and return a unordered html list.
     *
     * @see https://stackoverflow.com/questions/10779546/recursiveiteratoriterator-and-recursivedirectoryiterator-to-nested-html-lists/10780023#10780023
     *
     * @deprecated since 2023-11-22
     *
     * @param string $path path to a certain directory
     * @return string
     */
    public function dir($path = '/'): string
    {
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        $dom     = new DomDocument("1.0");
        $list    = $dom->createElement("ul");
        $dom->appendChild($list);
        $node  = $list;
        $depth = 0;
        foreach ($objects as $name => $object) {
            $pos = strpos($object->getFilename(), '.');
            //error_log("Position " . $pos . " in " . $object->getFilename());

            if (str_starts_with($object->getFilename(), '.') || str_starts_with($object->getFilename(), '..')) {
                continue;
            }

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
     * @deprecated since 2023-11-22
     *
     * @param $object
     * @return mixed
     */
    public function createListItem(&$dom, $file)
    {
        $e = $dom->createElement('a', $file->getFilename());
        $a = $dom->appendChild($e);

        if ($file->isFile()) {
            $extension = $file->getExtension();
            //error_log($extension);
            if (array_key_exists($extension, $this->filetypes)) {
                $bridge = $this->filetypes[$extension];
                $a->setAttribute('href', $bridge['prefix'] . WEBDAV_PREFIX . '/' . $file->getFilename());
                //error_log($bridge['prefix']);
            } else {
                $a->setAttribute('href', 'file:/' . $file->getPathname());
                $a->setAttribute('target', '_blank');
            }
        } else {
            //$a->setAttribute('href', $file->getPathname());
            $a->setAttribute('href', '#toggle');
        }
        $li = $dom->createElement('li');
        $li->appendChild($e);
        return $li;
    }

    /**
     * Returns the URL to the item depending on the extension.
     *
     * @return string
     */
    public function getHref()
    {
        $path_info = pathinfo($this->bean->path);
        if ( ! isset($path_info['extension'])) {
            return $this->bean->path;
        }
        $extension = $path_info['extension'];
        $href      = $this->bean->path;
        if (array_key_exists($extension, $this->filetypes)) {
            $bridge = $this->filetypes[$extension];
            $href   = str_replace(DMS_PATH, '', $href);
            $href   = $bridge['prefix'] . WEBDAV_PREFIX . $href;
        } else {
            $href = WEBDAV_PREFIX . str_replace(DMS_PATH, '', $href);
        }
        return $href;
    }

    /**
     * Returns the cut of URL to the item depending on the extension.
     *
     * @return string
     */
    public function getShortHref()
    {
        return str_replace(DMS_PATH, '', $this->bean->path);
    }

    /**
     * Returns wether the file is an template or not.
     *
     * @return bool
     */
    public function isTemplate(): bool
    {
        return $this->bean->template ? true : false;
    }

    /**
     * Return the machine bean.
     *
     * @return $machine
     */
    public function getMachine()
    {
        if ( ! $this->bean->machine) {
            $this->bean->machine = R::dispense('machine');
        }
        return $this->bean->machine;
    }

    /**
     * dispense a new bean.
     */
    public function dispense()
    {
        $this->bean->size = 0;
        //$this->bean->filemtime = date('Y-m-d H:i:s');
        //$this->bean->machine = null;
        $this->addConverter('filemtime', new Converter_Mysqldatetime());
    }

    /**
     * update the file bean.
     */
    public function update()
    {
        /*
        if ( ! $this->bean->machine_id) {
            $this->bean->machine_id = null;
            unset($this->bean->machine);
        }
        */
        $this->bean->ident = md5($this->bean->path);
        parent::update();
    }
}
