<?php

namespace Stenfrank\LaravelMailers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Abstract dynamic mailers.
 */
abstract class AbstractDynamicMailers
{
    /**
     * Set configs.
     *
     * @param  string $name
     * @param  int $key
     * @return array
     */
    protected function setConfigs($name, $key)
    {
        foreach (array_keys(Arr::except($this->configs, ['mailers'])) as $type) {
            $this->mailer[$name][Str::singular($type)] = $this->{'set'.Str::studly($type)}($key);
        }

        return $this->mailer[$name];
    }

    /**
     * Set transports.
     *
     * @param  int $key
     * @return string
     */
    protected function setTransports($key)
    {
        return Arr::get($this->configs, "transports.{$key}", null);
    }

    /**
     * Set hosts.
     *
     * @param  int $key
     * @return string
     */
    protected function setHosts($key)
    {
        return Arr::get($this->configs, "hosts.{$key}", null);
    }

    /**
     * Set ports.
     *
     * @param  int $key
     * @return int
     */
    protected function setPorts($key)
    {
        return (int) Arr::get($this->configs, "ports.{$key}", null);
    }

    /**
     * Set froms.
     *
     * @param  int $key
     * @return array
     */
    protected function setFroms($key)
    {
        return [
            'address' => Arr::get($this->configs, "froms.address.{$key}", null),
            'name' => Arr::get($this->configs, "froms.name.{$key}", null),
        ];
    }

    /**
     * Set encryptions.
     *
     * @param  int $key
     * @return string
     */
    protected function setEncryptions($key)
    {
        return (($encryption = Arr::get($this->configs, "encryptions.{$key}", null)) === 'null') ? null : $encryption;
    }

    /**
     * Set usernames.
     *
     * @param  int $key
     * @return string
     */
    protected function setUsernames($key)
    {
        return Arr::get($this->configs, "usernames.{$key}", null);
    }

    /**
     * Set passwords.
     *
     * @param  int $key
     * @return string
     */
    protected function setPasswords($key)
    {
        return Arr::get($this->configs, "passwords.{$key}", null);
    }
}
