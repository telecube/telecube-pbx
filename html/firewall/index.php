<?php
require("../init.php");

// get the ssh ports
$q = "select value from preferences where name = 'fw_ssh_ports';";
$data = array();
$res = $Db->pdo_query($q,$data,$dbPDO);
$ssh_ports = $res[0]['value'];



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/meta.php");?>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/title.php");?>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/css.php");?>

  </head>
  <body>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

    <div class="container">

      <h1>Firewall</h1>
      <div class="well">
        <p>Configure firewall options here.</p>
      </div>
    
      <form method="post" action="update.php">
        <div class="panel panel-primary"> 
          <div class="panel-heading"> 
            <h3 class="panel-title">SSH Ports</h3> 
          </div> 
          <div class="panel-body">
            <p>SSH access to the server is available on the standard port 22 or a non-standard port 32122</p>
            <p>Select the port(s) you want to allow SSh access on</p>
            <input type="hidden" name="firewall_update" value="ssh_update">
            <div class="radio">
              <label>
                <input type="radio" name="fw_ssh_port" id="optionsRadios1" value="22" <?php echo $ssh_ports == "22" ? " checked" : "";?>>
                Port 22 only
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="fw_ssh_port" id="optionsRadios2" value="32122" <?php echo $ssh_ports == "32122" ? " checked" : "";?>>
                Port 32122 only
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="fw_ssh_port" id="optionsRadios3" value="both" <?php echo $ssh_ports == "both" ? " checked" : "";?>>
                Both ports 22 and 32122
              </label>
            </div>
            <button type="submit" class="btn btn-primary">Update User/Pass</button>
          </div> 
        </div>
      </form>



    </div>

    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>
  
  </body>
</html>