<?php

namespace Backend\Lib\Menu;

class Menu implements \Iterator
{
    /**
     * @var MenuItem[]
     */
    protected $_items = [];

    /**
     * @var array
     */
    protected $_attr;

    /**
     * @var array Items keys cache. Used by iterator
     */
    private $_it;

    /**
     * @var int Current position in iterator array
     */
    private $_itpos;

    public function __construct($items = [], $attr = [])
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
        $this->_attr = $attr;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function getAttribute($key)
    {
        if (isset($this->_attr[$key])) {
            return $this->_attr[$key];
        }
    }

    public function getAttributes()
    {
        return $this->_attr;
    }

    public function getItems()
    {
        return $this->_items;
    }

    public function &addItem($title, $url = null, $attr = [], $children = [])
    {
        if (!is_object($title)) {
            $item = new MenuItem($title, $url, $attr, $children);
        } elseif ($title instanceof MenuItem) {
            $item = $title;
        } else {
            throw new \InvalidArgumentException("Menu::addItem failed: Invalid MenuItem instance");
        }
        $hash = spl_object_hash($item);
        $this->_items[$hash] = $item;
        return $this->_items[$hash];
    }

    public function removeItem(MenuItem $item)
    {
        $hash = spl_object_hash($item);
        if (isset($this->_items[$hash])) {
            unset($this->_items[$hash]);
        }
    }

    public function count()
    {
        return count($this->_items);
    }

    public function toArray()
    {
        $list = [];
        foreach ($this->_items as $item) {
            $list[] = $item->toArray();
        }
        return $list;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $pos = $this->_itpos;
        return $this->_items[$this->_it[$pos]];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->_itpos++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        $pos = $this->_itpos;
        return (isset($this->_it[$pos])) ? $this->_it[$pos] : null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        $pos = $this->_itpos;
        return (isset($this->_it[$pos])) ? true : false;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_it = array_keys($this->_items);
        $this->_itpos = 0;
    }
}