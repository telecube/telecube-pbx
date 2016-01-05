<?php
require("../init.php");

// get a list of current extensions
$q = "select name, secret, label from sip_devices order by name;";
$data = array();
$res = $Db->pdo_query($q,$data,$dbPDO);
//print_r($res);
$sip_devices = $res;
$extensions = array();
$j = count($res);
for($i=0;$i<$j;$i++) { 
  $extensions[] = $res[$i]['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/meta.php");?>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/title.php");?>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/css.php");?>



    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>
 

    <!-- bootstrap -->

    <!-- x-editable (bootstrap version) -->
    

<script type="text/javascript">

  $(document).ready(function() {
      //toggle `popup` / `inline` mode
      $.fn.editable.defaults.mode = 'popup';     
      
      <?php
      $j = count($sip_devices);
      for($i=0;$i<$j;$i++) { 
        echo '$("#label-'.$sip_devices[$i]['name'].'").editable();'."\n";
        echo '$("#secret-'.$sip_devices[$i]['name'].'").editable();'."\n";
      }
      ?>


    $('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });


  });

function del_ext(){
  alert("hello");

}
</script>


  </head>
  <body>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

    <div class="container">

      <h1>Extensions</h1>
      <div class="well">
        <p>Extensions are the points on this PBX server that your voip devices connect to.</p>
      </div>


      <div class="row">
        
        <div class="col-lg-4">

          <form method="post" action="add-new.php">
            <div class="panel panel-default"> 
              <div class="panel-heading"> 
                <h3 class="panel-title">Add New Extension</h3> 
              </div> 
              <div class="panel-body">
                <p>Select a new extension number.</p>

                <input type="hidden" name="extension_post_form" value="add_new">
                
                <select class="form-control" name="ext">
                  <?php
                    $j = 1000;
                    for($i=0;$i<$j;$i++) { 
                      $ext = $i+1000;
                      if(!in_array($ext, $extensions)){
                        echo "<option>".$ext."</option>";
                      }
                    }
                  ?>
                </select>            

                <p></p>

                <button type="submit" class="btn btn-primary">Add New Extension</button>
              </div> 
            </div>
          </form>

        </div>

      </div>



      <?php
        $x=0;
        $j = count($sip_devices);
        for($i=0;$i<$j;$i++) { 
          echo $x==0 ? "\t".'<div class="row">'."\n" : "";
          echo "\t\t".'<div class="col-lg-4">'."\n";

          echo "\t\t\t".'<form method="post" action="add-new.php">'."\n";

          echo "\t\t\t\t".'<div class="panel panel-default">'."\n";
          echo "\t\t\t\t\t".'<div class="panel-heading">'."\n";
          echo "\t\t\t\t\t\t".'<h3 class="panel-title">Ext: '.$sip_devices[$i]['name'].'</h3>'."\n";
          echo "\t\t\t\t\t".'</div>'."\n";
          echo "\t\t\t\t".'<div class="panel-body">'."\n";



          echo "\t\t\t\t".'<p>Label: <a href="#" id="label-'.$sip_devices[$i]['name'].'" data-type="text" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Label">'.$sip_devices[$i]['label'].'</a></p>'."\n";
          
          echo "\t\t\t\t".'<p>Password: <a href="#" id="secret-'.$sip_devices[$i]['name'].'" data-type="text" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Password">'.$sip_devices[$i]['secret'].'</a></p>'."\n";
          
          echo "\t\t\t\t".''."\n";
          
          echo "\t\t\t\t".'<a class="btn btn-sm btn-danger" data-toggle="confirmation" data-title="Really, delete this extension?" data-href="delete.php?ext='.$sip_devices[$i]['name'].'" data-original-title="" title="">Delete</a>'."\n";
          
          echo "\t\t\t\t".'</div>'."\n";
          echo "\t\t\t\t".'</div>'."\n";



          echo "\t\t".'</div>'."\n";
          echo $x==2 || $i == $j-1 ? "\t".'</div>'."\n" : "";
          $x++;
          $x=$x==3?0:$x;
        }
      ?>




    </div>


  </body>
</html>