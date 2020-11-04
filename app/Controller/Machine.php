<?php
/**
 * KSM.
 *
 * @package KSM
 * @subpackage Controller
 * @author $Author$
 * @version $Id$
 */

/**
 * Machine controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Machine extends Controller
{
    /**
     * Holds the machine bean.
     *
     * @var RedBeanPHP/OODBean
     */
    public $machine;

    /**
     * Constructor
     *
     * @param int $id ID of the contract to output as PDF
     */
    public function __construct($id)
    {
        session_start();
        Auth::check();
        $this->machine = R::load('machine', $id);
    }

    /**
     * Install an article into a machine.
     *
     * This is called by a POST request from the machine edit template and it installedpart sub template.
     */
    public function install()
    {
        $ok = false;
        $html = '';

        $ip = R::dispense('installedpart');

        $article = R::load('article', Flight::request()->data->article_id);

        $ip->article = $article;
        $ip->stamp = Flight::request()->data->stamp;
        $ip->purchaseprice = Flight::request()->data->purchaseprice;
        $ip->salesprice = Flight::request()->data->salesprice;
        $ip->machine = $this->machine;

        if (Flight::request()->data->adopt) {
            $article->purchaseprice = $ip->purchaseprice;
            $article->salesprice = $ip->salesprice;
        }

        R::begin();
        try {
            R::store($article);
            R::store($ip);
            R::commit();
            $ok = true;

            ob_start();
            Flight::render('model/machine/tables/installedpart/datarow', [
                'record' => $this->machine,
                '_article' => $article,
                '_installedpart' => $ip
            ]);
            $html = ob_get_contents();
            ob_end_clean();
        } catch (\Exception $e) {
            R::rollback();
            error_log($e);
            $ok = false;
        }

        $result = [
            'okay' => $ok,
            'html' => $html
        ];

        Flight::jsonp($result, 'callback');
    }
}
