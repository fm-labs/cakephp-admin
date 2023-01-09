<?php
declare(strict_types=1);

namespace Admin\Core;

class AdminPluginCollection implements \Iterator
{
    /**
     * @var array
     */
    protected array $plugins = [];

    protected array $names = [];

    protected int $pos = 0;

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
     * @param string $pluginName Plugin name
     * @return bool
     */
    public function has(string $pluginName): bool
    {
        return isset($this->plugins[$pluginName]);
    }

    /**
     * Return the current element
     *
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current(): mixed
    {
        $name = $this->names[$this->pos] ?? null;

        return $this->plugins[$name] ?? null;
    }

    /**
     * Move forward to next element
     *
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
        if ($this->pos < count($this->names)) {
            $this->pos++;
        }
    }

    /**
     * Return the key of the current element
     *
     * @link https://php.net/manual/en/iterator.key.php
     * @return string|float|int|bool|null scalar on success, or null on failure.
     */
    public function key(): mixed
    {
        return $this->names[$this->pos] ?? null;
    }

    /**
     * Checks if current position is valid
     *
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        return isset($this->names[$this->pos]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(): void
    {
        $this->pos = 0;
    }
}
