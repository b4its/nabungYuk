<script>
<?php
    $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : '';
    $statusAlert = isset($_SESSION['statusAlert']) ? $_SESSION['statusAlert'] : '';
    
    if ($messages){
      echo 'swal({';
      echo 'text: "'.$messages.'",';
      echo 'icon: "'.$statusAlert.'",';
      echo '});';
    }
unset($_SESSION['messages']);

        
  ?>
</script>
