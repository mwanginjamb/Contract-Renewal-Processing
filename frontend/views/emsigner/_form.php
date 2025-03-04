<?php
$reference = Yii::$app->session->get('reference');
$folder = "./data/" . $reference;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("form").trigger("submit");
    });
</script>
<center>
    <h2>Redirecting to E-Sign Gateway..... Please wait.....</h2>
</center>
<form action="<?= env('SIG_ENDPOINT') ?>" id="frmdata" method="post">
    <center></br></br></br></br>
        <input id="Parameter1" Name="Parameter1" type="hidden"
            value="<?php echo file_get_contents($folder . "/encrypted_sessionkey.txt"); ?>" />

        <input id="Parameter2" Name="Parameter2" type="hidden"
            value="<?php echo file_get_contents($folder . "/encrypted_json_data.txt"); ?>" />

        <input id="Parameter3" Name="Parameter3" type="hidden"
            value="<?php echo file_get_contents($folder . "/encrypted_hashof_json_data.txt"); ?>" />

        <!-- <button  style="height:50px; background-color:#FFCC33" type="submit" name="formAction2" id="btnEsignKYC" value="EsignWithASP">E-Sign</button> -->

    </center>

</form>