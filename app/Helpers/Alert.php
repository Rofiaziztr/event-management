<?php

namespace App\Helpers;

class Alert
{
    /**
     * Flash a success message to the session
     *
     * @param string $message
     * @param array $options Additional options for the alert
     * @return void
     */
    public static function success(string $message, array $options = [])
    {
        session()->flash('alert_type', 'success');
        session()->flash('alert_message', $message);
        
        if (!empty($options)) {
            session()->flash('alert_options', json_encode($options));
        }
    }
    
    /**
     * Flash an error message to the session
     *
     * @param string $message
     * @param array $options Additional options for the alert
     * @return void
     */
    public static function error(string $message, array $options = [])
    {
        session()->flash('alert_type', 'error');
        session()->flash('alert_message', $message);
        
        if (!empty($options)) {
            session()->flash('alert_options', json_encode($options));
        }
    }
    
    /**
     * Flash a warning message to the session
     *
     * @param string $message
     * @param array $options Additional options for the alert
     * @return void
     */
    public static function warning(string $message, array $options = [])
    {
        session()->flash('alert_type', 'warning');
        session()->flash('alert_message', $message);
        
        if (!empty($options)) {
            session()->flash('alert_options', json_encode($options));
        }
    }
    
    /**
     * Flash an info message to the session
     *
     * @param string $message
     * @param array $options Additional options for the alert
     * @return void
     */
    public static function info(string $message, array $options = [])
    {
        session()->flash('alert_type', 'info');
        session()->flash('alert_message', $message);
        
        if (!empty($options)) {
            session()->flash('alert_options', json_encode($options));
        }
    }
}