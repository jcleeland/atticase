<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* ManagedAppConfiguration File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Beta\Microsoft\Graph\Model;

/**
* ManagedAppConfiguration class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class ManagedAppConfiguration extends ManagedAppPolicy
{

     /**
     * Gets the customSettings
    * A set of string key and string value pairs to be sent to apps for users to whom the configuration is scoped, unalterned by this service
     *
     * @return array|null The customSettings
     */
    public function getCustomSettings()
    {
        if (array_key_exists("customSettings", $this->_propDict)) {
           return $this->_propDict["customSettings"];
        } else {
            return null;
        }
    }

    /**
    * Sets the customSettings
    * A set of string key and string value pairs to be sent to apps for users to whom the configuration is scoped, unalterned by this service
    *
    * @param KeyValuePair[] $val The customSettings
    *
    * @return ManagedAppConfiguration
    */
    public function setCustomSettings($val)
    {
        $this->_propDict["customSettings"] = $val;
        return $this;
    }


     /**
     * Gets the settings
    * List of settings contained in this App Configuration policy
     *
     * @return array|null The settings
     */
    public function getSettings()
    {
        if (array_key_exists("settings", $this->_propDict)) {
           return $this->_propDict["settings"];
        } else {
            return null;
        }
    }

    /**
    * Sets the settings
    * List of settings contained in this App Configuration policy
    *
    * @param DeviceManagementConfigurationSetting[] $val The settings
    *
    * @return ManagedAppConfiguration
    */
    public function setSettings($val)
    {
        $this->_propDict["settings"] = $val;
        return $this;
    }

}
