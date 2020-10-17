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
 * Article controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Article extends Controller
{
    /**
     * Holds the article bean.
     *
     * @var RedBeanPHP/OODBean
     */
    public $article;

    /**
     * Constructor
     *
     * @param int $id ID of the contract to output as PDF
     */
    public function __construct($id)
    {
        session_start();
        Auth::check();
        $this->article = R::load('article', $id);
    }

    /*
     * Sets the appointment to completed.
     */
    public function chartdata()
    {
        $labels = [];
        $data_purchaseprice = [];
        $data_salesprice = [];
        $stats = R::getAll("SELECT DATE_FORMAT(stamp, '%Y-%m-%d') AS label, purchaseprice, salesprice FROM artstat WHERE article_id = ? ORDER BY stamp", [$this->article->getId()]);
        foreach ($stats as $id => $stat) {
            $labels [] = $stat['label'];
            $data_purchaseprice[] = $stat['purchaseprice'];
            $data_salesprice[] = $stat['salesprice'];
        }
        $result = [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => I18n::__('article_chart_purchaseprice'),
                        'fill' => false,
                        'backgroundColor' => 'rgb(0, 128, 212)',
                        'borderColor' => 'rgb(0, 128, 212)',
                        'data' => $data_purchaseprice
                    ],
                    [
                        'label' => I18n::__('article_chart_salesprice'),
                        'fill' => false,
                        'backgroundColor' => '#009933',
                        'borderColor' => '#009933',
                        'data' => $data_salesprice
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'title' => [
                    'display' => false,
                    'text' => I18n::__('article_chart_pricedevelopment')
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'interset' => false,
                    'backgroundColor' => '#666666'
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'scales' => [
                    'xAxes' => [
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => I18n::__('article_chart_xaxis_label')
                        ]
                    ],
                    'yAxes' => [
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => I18n::__('article_chart_yaxis_label')
                        ]
                    ]
                ]
            ]
        ];
        Flight::json($result);
    }
}
