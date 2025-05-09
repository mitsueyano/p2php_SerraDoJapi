<?php //--- Função de logout ---//

    session_start();
    session_destroy();
    header("Location: ../telas/index/index.php");
    exit();

?>