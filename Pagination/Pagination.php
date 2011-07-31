<?php

namespace Redfrog\Pagination;

use DoctrineExtensions\Paginate\Paginate;

class Pagination
{
    protected $query;
    protected $items = 0;
    protected $page = 1;
    protected $pages = 1;
    protected $itemsPerPage = 50;

    public static function create($query, $page, $items = 50)
    {
        return new self($query, $page, $items);
    }

    public function __construct($query, $page, $itemsPerPage = 50)
    {
        $this->setPage($page);
        $this->setItemsPerPage($itemsPerPage);

        $this->setItems( Paginate::getTotalQueryResults($query) );

        $query->setFirstResult($this->getPage() * $this->getItemsPerPage());
        $query->setMaxResults($this->getItemsPerPage());
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPages()
    {
        return ceil($this->items / $this->getItemsPerPage());
    }

    public function setItemsPerPage($items)
    {
        $this->itemsPerPage = $items;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    public function getPagination($url, array $params = array())
    {
        $page = $this->getPage()+1;
        $pages = ceil($this->getPages());
        $result = array();

        if ($page > 1 && $pages > 1) {
            $result[] = '<li><a href="' . $this->generateUrl($url, $params, $page-1) . '" class="prev">«</a></li>';
        }

        if ($page > 4) {
            $result[] = '<li><a href="' . $this->generateUrl($url, $params, 1) . '" class="first">1</a></li>';
            if ($page-1 > 4) {
                $result[] = '<li>...</li>';
            }
        }

        for ($i = $page-3; $i < $page+4; $i++) {
            if ($i > 0 && $i <= $pages) {
                $result[] = '<li><a href="' . $this->generateUrl($url, $params, $i) . '" class="' . (($i != $page) ?: 'active') . '">' . $i . '</a></li>';
            }
        }

        if ($page+3 < $pages) {
            if ($page+4 < $pages) {
                $result[] = '<li>...</li>';
            }
            $result[] = '<li><a href="' . $this->generateUrl($url, $params, $pages) . '" class="last">' . $pages . '</a></li>';
        }

        if ($page < $pages && $pages > 1) {
            $result[] = '<li><a href="' . $this->generateUrl($url, $params, $page+1) . '" class="next">»</a></li>';
        }

        return '<ol class="Pagination">' . implode(PHP_EOL, $result) . '</ol>';
    }

    protected function generateUrl($route, $params, $page)
    {
        return str_replace('{page}', $page, $route);
    }
}
