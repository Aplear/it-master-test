<?php
use \yii\helpers\Url;

?>
<?php foreach ($mails as $mail):?>

    <div class="row">
        <div class="col-xs-6 col-md-3">
            <div class="thumbnail">

                <div class="caption">
                    <h3>
                        <span><b>From:</b> <?=$mail->from?></span>
                        <br />
                        <span><b>To:</b> <?=$mail->to?></span>
                    </h3>
                    <p>
                        <?=$mail->date?>
                    </p>
                    <p>
                        <?=$mail->subject?>
                    </p>
                    <p><a href="<?=Url::to(['/mail/show?id='.$mail->uid])?>" class="btn btn-primary" role="button">Show</a>
                        <a href="<?=Url::to(['/mail/mail-delete?id='.$mail->uid])?>" class="btn btn-danger" role="button">Delete</a></p>
                </div>
            </div>
        </div>
    </div>





<?php endforeach;?>
