<?php
namespace Kizilare\Api\Togglr;

/**
 * Class Read
 *
 * @package Kizilare\Api\Togglr
 */
class Read extends Abstracted
{
    /**
     * Gets the identifier for the current running project.
     *
     * @return integer
     */
    public function getRunningProjectId()
    {
        if (false == $entry = $this->getCurrentTimeEntry()) {
            return 0;
        }
        return $entry['pid'];
    }

    /**
     * Gets the details for the current project running.
     *
     * @return array
     */
    public function getCurrentTimeEntry()
    {
        $entry = $this->requestApi( 'time_entries/current' );
        if (empty( $entry['data'] )) {
            return false;
        }
        return $entry['data'];
    }

    /**
     * Return current user information.
     *
     * @return array
     */
    public function getUser()
    {
        return $this->requestApi( 'me' );
    }

    /**
     * Gets the list of projects for the current workspace.
     *
     * @return array
     */
    public function getProjects()
    {
        $projects_data = $this->requestApi( 'workspaces/' . $this->workspace_id . '/projects' );
        return $this->groupCollection( $projects_data, 'id' );
    }

    /**
     * Get a summary of a day.
     *
     * @param $date
     * @return mixed
     */
    public function getDayDetails( $date )
    {
        $projects = $this->getProjects();

        $week = date( 'W', $date );
        $year = date( 'Y', $date );
        $from = date( "Y-m-d", strtotime( "{$year}-W{$week}-1" ) );
        $to = date( "Y-m-d", strtotime( "{$year}-W{$week}-7" ) );
        $params = http_build_query(
            array(
                'start_date' => $from . 'T00:00:00+01:00',
                'end_date'   => $to . 'T23:59:59+01:00'
            )
        );
        $entries_data = $this->requestApi( 'time_entries?' . $params );

        foreach ($entries_data as & $entry) {
            $entry['project'] = $projects[$entry['pid']]['name'];
            $entry['project_id'] = $entry['pid'];
        }

        return $entries_data;
    }

    /**
     * Groups elements by main key.
     *
     * @param array $collection List of elements to group.
     * @param string $use_key Key used for the group.
     * @return array
     */
    protected function groupCollection( $collection, $use_key )
    {
        $result = array();
        foreach ($collection as $data) {
            $key = $data[$use_key];
            $result[$key] = $data;
        }
        return $result;
    }


}