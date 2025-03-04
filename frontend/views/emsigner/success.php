<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:29 PM
 */

use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model frontend\models\AgendaDocument */

$this->title = 'Gateway Success';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '#'];
?>
<?php
if (Yii::$app->session->hasFlash('success')) {
    print ' <div class="alert alert-success alert-dismissable">
                                 ';
    echo Yii::$app->session->getFlash('success');
    print '</div>';
} else if (Yii::$app->session->hasFlash('error')) {
    print ' <div class="alert alert-danger alert-dismissable">
                                 ';
    echo Yii::$app->session->getFlash('error');
    print '</div>';
}
?>
<div class="row">
    <div class="col">
        <?= $this->render('_actions'); ?>
    </div>
</div>
<div class="card card-info">
    <div class="card-header">
        <h2 class="card-title">Signed Document</h2>
    </div>
    <div class="card-body">
        <!-- <p class="card-text text"> Session: <?= $sessionRef ?></p>
        <p class="card-text my-3 text-muted">Signer: <?= Yii::$app->user->identity->id ?></p> -->

        <?php if ($post['Returnvalue']) { ?>
            <iframe src="data:application/pdf;base64,<?= $content; ?>" height="950px" width="100%"></iframe>
        <?php } else { ?>
            <div class="alert alert-info p-3 text-bold text-center rounded">Could not retrieve the signed Copy. Sorry!</div>
        <?php } ?>
    </div>






    <!--My Bs Modal template  --->

    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" style="position: absolute">Approval Comment</h4>
                </div>
                <div class="modal-body">
                    <form id="approval-comment" action="">

                        <div class="card">
                            <div class="card-body">
                                <textarea class="form-control" name="comment" rows="4"
                                    placeholder="Enter your approval comment here.." required
                                    maxlength="500"></textarea>
                                <br>
                                <input type="hidden" readonly name="documentNo" class="form-control">
                                <input type="hidden" readonly name="Approver_No" class="form-control">
                                <input type="hidden" readonly name="Table_ID" class="form-control">
                                <input type="hidden" readonly name="Approval_Entry_No" class="form-control">

                            </div>
                            <div class="card-footer">
                                <div class="input-group">
                                    <input type="submit" class="btn btn-outline-primary" id="reject"
                                        value="Save & Approve">
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>

            </div>
        </div>
    </div>


</div>

<?php
$absoluteUrl = \yii\helpers\Url::home(true);
print '<input type="hidden" id="ab" value="' . $absoluteUrl . '" />';
$script = <<<JS

    var absolute = $('#ab').val();
    /*Post Approval comment*/
    
    $('form#approval-comment').on('submit', function(e){
        e.preventDefault();

        //Disable btn
        $('#reject').val('Sending ...');
        $('#reject').prop('disabled', true);
        
        var url = absolute + $(this).attr('action'); 
        var data = $(this).serialize();
        
        
        $.post(url, data).done(function(msg){
          // $('.modal').modal('hide');
            var confirm = $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
            
            setTimeout(confirm, 1000);
            
        },'json');
        
       
    });
    
    
/** Approve Action */

    $('.approve').on('click',function(e){
            e.preventDefault();
            var data = $(this).data();
            //console.log(data.entry_no);
            $('input[name=documentNo]').val(data.document_no);
            $('input[name=Approver_No]').val(data.approver_no);
            $('input[name=Table_ID]').val(data.table_id);
            $('input[name=Approval_Entry_No]').val(data.entry_no);

            let actionUrl = data.action;

            $('form#approval-comment').attr('action', actionUrl);
    
            $('.modal').modal('show');                            
    
         });


    /*Reject Action*/
    
        $('.reject').on('click',function(e){
            e.preventDefault();
            
            var docno = $(this).attr('rel');
            var Approver_No = $(this).attr('rev');
            var Table_ID = $(this).attr('name');
            var data = $(this).data();
            //console.log(data.entry_no);
           
            $('input[name=documentNo]').val(docno);
            $('input[name=Approver_No]').val(Approver_No);
            $('input[name=Table_ID]').val(Table_ID);
            $('input[name=Approval_Entry_No]').val(data.entry_no);
            
    
            $('.modal').modal('show');                            
    
         });
        
      /*Submit approval comment */
      
      
        
        /*Handle dismissal event of modal */
        $('.modal').on('hidden.bs.modal',function(){
             var absolute = $('#ab').val();
             var redirectTo = absolute + 'approvals'
            //var reld = location.reload(true);
           setTimeout(function(){
            window.location.href = redirectTo;
           },1000);
        });

    /* Data tables */
JS;

$this->registerJs($script, View::POS_READY);
