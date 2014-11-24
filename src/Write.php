<?php

namespace Kizilare\Api\Togglr;

/**
 * Class Write
 *
 * @package Kizilare\Api\Togglr
 */
Class Write extends Abstracted
{
    /**
     * Switches the current activity for a new one.
     *
     * @param string $message Message for the new task.
     * @param string $project_id Project ID for the new task, it keeps the same if none specified.
     */
    public function switchTask( $message, $project_id = null )
    {
        $read = new Read( $this->api_key );
        if ($project_id == null) {
            $project_id = $read->getRunningProjectId();
        }
        $new_data = array(
            'time_entry' => array(
                'description' => $message,
                'pid'         => $project_id
            )
        );
        $this->requestApi( 'time_entries/start', $new_data );
    }
}
