<?php
/*
 * This file is part of lmsimple.
 *
 * darany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * darany is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 */


if (function_exists('apache_get_modules')) {
  $modules = apache_get_modules();
  $mod_rewrite = in_array('mod_rewrite', $modules);
} else {
  $mod_rewrite =  getenv('HTTP_MOD_REWRITE')=='On' ? true : false ;
}

$allow_overwrite =  getenv('ALLOW_OVERWRITE');
$mod_rewrite =  getenv('HTTP_MOD_REWRITE');
$server_software = getenv('SERVER_SOFTWARE');

if ($mod_rewrite == "") $mod_rewrite = '<b>.htaccess not visited</b>';
if ($allow_overwrite == "") $allow_overwrite = '<b>Off</b>';

//Check if we can access to the configuration file
$dbExists = FALSE;
$rs = NULL;
$pathConfigFile = realpath(join(DIRECTORY_SEPARATOR, array('application', 'config', 'database.php')));
$configFileExists = file_exists($pathConfigFile);
if ($configFileExists ) {
    include $pathConfigFile;
    //Try to connect to database
    $dbConn = @mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']);
    if ($dbConn) {
        //Try to select LMS database
        if (@mysql_select_db($db['default']['database'], $dbConn)) {
            //Try to get the signature of the schema
            $dbExists = TRUE;
            $sql = "SELECT TABLE_NAME, MD5(GROUP_CONCAT(CONCAT(TABLE_NAME, COLUMN_NAME, COALESCE(COLUMN_DEFAULT, ''), IS_NULLABLE, COLUMN_TYPE, COALESCE(COLLATION_NAME, '')) SEPARATOR ', ')) AS signature"
                        . " FROM information_schema.columns"
                        . " WHERE table_schema =  DATABASE()"
                        . " GROUP BY TABLE_NAME";
            $rs = @mysql_query($sql, $dbConn);
        }
    }
}
?>
<html>
    <head>
    <title>Darany Requirements</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta charset="UTF-8">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    
        <div class="container-fluid">

            <h1>Darany Requirements</h1>
            
            <h2>Web Server</h2>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                      <th>Requirement</th>
                      <th>Value / Description</th>
                    </tr>
                  </thead>
                  <tbody>
                      <tr><td><i class="icon-info-sign"></i>&nbsp;Server software</td><td><?php echo $server_software; ?></td></tr>

                      <tr><td><?php if (strtolower($allow_overwrite) == "on") {?><i class="icon-ok-sign"></i><?php } else { ?><i class="icon-remove-sign"></i><?php } ?>
                      &nbsp;Allow overwrite (.htaccess files)</td><td><?php echo $allow_overwrite; ?> (used for cool URLs).</td></tr>

                      <tr><td><?php if (strtolower($mod_rewrite) == "on") {?><i class="icon-ok-sign"></i><?php } else { ?><i class="icon-remove-sign"></i><?php } ?>
                      &nbsp;Apache module rewrite (mod_rewrite)</td><td><?php echo $mod_rewrite; ?> (used for cool URLs).</td></tr>

                      <?php if (extension_loaded('openssl')) {?>
                      <tr><td><i class="icon-ok-sign"></i>&nbsp;openssl is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="icon-exclamation-sign"></i>&nbsp;openssl IS NOT LOADED.</td>
                      <?php } ?><td>PHP Extension openssl speeds up the log in page</td></tr>
                      
                      <?php if (extension_loaded('ldap')) {?>
                      <tr><td><i class="icon-ok-sign"></i>&nbsp;ldap is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="icon-exclamation-sign"></i>&nbsp;ldap IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension ldap is optional and allows you to use LDAP for authentication.</td></tr>
                      
                      <?php if (extension_loaded('zip')) {?>
                      <tr><td><i class="icon-ok-sign"></i>&nbsp;zip is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="icon-exclamation-sign"></i>&nbsp;zip IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension zip allows you to use the export to Excel feature.</td></tr>
                      
                      <?php if (extension_loaded('xml')) {?>
                      <tr><td><i class="icon-ok-sign"></i>&nbsp;xml is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="icon-exclamation-sign"></i>&nbsp;xml IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension xml allows you to use the export to Excel feature.</td></tr>
                      
                      <?php if (extension_loaded('gd')) {?>
                      <tr><td><i class="icon-ok-sign"></i>&nbsp;gd is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="icon-exclamation-sign"></i>&nbsp;gd IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension gd2 allows you to use the export to Excel feature.</td></tr>
                      
                      <?php if (extension_loaded('mysqli')) {?>
                      <tr><td><i class="icon-ok-sign"></i>&nbsp;mysqli is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="icon-exclamation-sign"></i>&nbsp;mysqli IS NOT LOADED</td>
                      <?php } ?><td>mysqli is the recommended database driver.</td></tr>

                      
                  </tbody>
            </table>

            <h2>Database</h2>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                      <th>Requirement</th>
                      <th>Value / Description</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php if ($configFileExists) { ?><tr><td><i class="icon-ok-sign"></i>&nbsp;Configuration file</td><td>Found</td></tr>
                      <?php } else { ?><tr><td><i class="icon-remove-sign"></i>&nbsp;Configuration file</td><td>Not Found</td></tr>
                      <?php } ?>
                      
                      <?php if ($dbConn) { ?><tr><td><i class="icon-ok-sign"></i>&nbsp;Database connection</td><td>OK</td></tr>
                      <?php } else { ?><tr><td><i class="icon-remove-sign"></i>&nbsp;Database connection</td><td>Error</td></tr>
                      <?php } ?>
                      
                      <?php if ($dbExists) { ?><tr><td><i class="icon-ok-sign"></i>&nbsp;Database name</td><td>Found</td></tr>
                      <?php } else { ?><tr><td><i class="icon-remove-sign"></i>&nbsp;Database name</td><td>Doesn't exist</td></tr>
                      <?php } ?>

                      <?php if ($rs) { ?><tr><td><i class="icon-ok-sign"></i>&nbsp;Database query</td><td>OK</td></tr>
                      <?php } else { ?><tr><td><i class="icon-remove-sign"></i>&nbsp;Database query</td><td>Error</td></tr>
                      <?php } ?>
                  </tbody>
            </table>
        </div>
    </body>
</html>