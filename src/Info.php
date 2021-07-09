<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * This code is licensed under MIT license!
 */

namespace Livck;

use GuzzleHttp\Exception\GuzzleException;

class Info
{
    private $livck;

    public function __construct(Livck $livck)
    {
        $this->livck = $livck;
    }

    /**
     * @throws GuzzleException
     */
    public function getCategories()
    {
        return $this->livck->get('categories');
    }

    /**
     * @throws GuzzleException
     */
    public function getMonitors()
    {
        return $this->livck->get('monitors');
    }

    /**
     * @throws GuzzleException
     */
    public function getAlerts()
    {
        return $this->livck->get('alerts');
    }

    /**
     * @throws GuzzleException
     */
    public function getState()
    {
        return $this->livck->get('alerts');
    }

    /**
     * @throws GuzzleException
     */
    public function getMonitor()
    {
        return $this->livck->get('monitor/homepage');
    }

    /**
     * @throws GuzzleException
     */
    public function getIncidents()
    {
        return $this->livck->get('monitor/homepage/incidents');
    }

}