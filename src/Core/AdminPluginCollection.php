<?php
declare(strict_types=1);

namespace Admin\Core;

class AdminPluginCollection implements \Iterator
{
    /**
     * @var array
     */
    protected $plugins = [];

    protected $names = [];

    protected $pos = 0;

    /**
     * @param \Admin\Core\AdminPluginInterface $plugin The admin plugin.
     * @return $this
     */
    public function add(AdminPluginInterface $plugin)
    {
        $name = $plugin->getName();
        $this->plugins[$name] = $plugin;
        $this->names[] = $name;

        return $this;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $name = $this->names[$this->pos] ?? null;

        return $this->plugins[$name] ?? null;
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        if ($this->pos < count($this->names)) {
            $this->pos++;
        }
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return string|float|int|bool|null scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->names[$this->pos] ?? null;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->names[$this->pos]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->pos = 0;
    }
}
