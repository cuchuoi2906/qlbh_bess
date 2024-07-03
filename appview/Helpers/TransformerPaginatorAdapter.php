<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/25/18
 * Time: 23:08
 */

namespace AppView\Helpers;


use League\Fractal\Pagination\PaginatorInterface;

class TransformerPaginatorAdapter implements PaginatorInterface
{

    public $totalItems;
    public $currentPage;
    public $totalPage;
    public $itemsPerPage;
    public $pageName;

    /**
     * TransformerPaginatorAdapter constructor.
     * @param int $total_items
     * @param int $current_page
     * @param int $total_page
     * @param int $items_per_page
     * @param string $page_name
     */
    public function __construct($total_items, $current_page, $items_per_page, $page_name = 'page')
    {

        $this->totalItems = (int)$total_items;
        $this->currentPage = (int)$current_page;
        $this->totalPage = (int)ceil($total_items / $items_per_page);
        $this->itemsPerPage = (int)$items_per_page;
        $this->pageName = $page_name;

    }

    public function getCount()
    {
        // TODO: Implement getCount() method.
        return $this->totalPage;
    }

    public function getCurrentPage()
    {
        // TODO: Implement getCurrentPage() method.
        return $this->currentPage;
    }

    public function getLastPage()
    {
        // TODO: Implement getLastPage() method.
        return $this->totalPage;
    }

    public function getPerPage()
    {
        // TODO: Implement getPerPage() method.
        return $this->itemsPerPage;
    }

    public function getTotal()
    {
        // TODO: Implement getTotal() method.
        return $this->totalItems;
    }

    public function getUrl($page)
    {
        // TODO: Implement getUrl() method.
        $get = $_GET;
        $get[$this->pageName] = (int)$page;

        $current_url = explode('?', current_url())[0];

        return $current_url . '?' . http_build_query($get);
    }

}