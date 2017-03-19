<?php

namespace Backend\Lib\Menu;

use Cake\View\View;

/**
 * Class MenuItem
 * @package Backend\Lib\Menu
 *
 * @property string $title Title
 * @property mixed $url Url
 * @property array $attr Attributes
 * @property Menu $children Children
 *
 */
class MenuItem implements \ArrayAccess
{
    protected $_title;

    protected $_url;

    protected $_attr;

    protected $_children;

    public function __construct($title, $url = null, array $attr = [], $children = [])
    {
        if (is_array($title)) {
            if (isset($title['data-icon'])) {
                $title['attr'] = ['data-icon' => $title['data-icon']];
                unset($title['data-icon']);
            }

            extract($title, EXTR_IF_EXISTS);
        }

        $this->_title = $title;
        $this->_url = $url;
        $this->_attr = $attr;
        $this->_children = new Menu($children);
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function getAttributes()
    {
        return $this->_attr;
    }

    public function &getChildren()
    {
        return $this->_children;
    }

    public function toArray()
    {
        return [
            'title' => $this->_title,
            'url' => $this->_url,
            'attr' => $this->_attr,
            'children' => $this->_children->toArray()
        ];
    }

    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return in_array($offset, ['title', 'url', 'attributes', 'attr', 'children', '_children']);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        switch ($offset) {
            case 'title':
                return $this->getTitle();
            case 'url':
                return $this->getUrl();
            case 'attributes':
            case 'attr':
                return $this->getAttributes();
            case 'children':
            case '_children': // legacy
                return $this->getChildren();
            default:
                return null;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @throws \RuntimeException
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Can not set value for this object');
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @throws \RuntimeException
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Can not unset value for this object');
    }
}