<?php
    if (CONFIGURATION_INC == 1)
        return;

    define("CONFIGURATION_INC", 1);

    define("CONF_ADMINID", "googlecal");        /* Your MySQL userid */
    define("CONF_ADMINPASS", "password");       /* Your MySQL password in plain text */
    define("CONF_LOCATION", "Localhost");       /* Location of database */
    define("CONF_DATABASE", "GoogleCal");       /* Database to connect to */
?>
