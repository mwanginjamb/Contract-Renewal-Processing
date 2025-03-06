<div class="row">
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Original Contract</h3>
            </div>
            <div class="card-body">
                <?php if (!$model->original_contract_path)
                    echo 'Original Contract not uploaded yet.';
                else
                    print '<iframe src="data:application/pdf;base64,' . $content . '" height="950px" width="100%"></iframe>';
                ?>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Signed Contract</h3>
            </div>
            <div class="card-body">
                <div class="m-3">
                    <?php if (!$model->signed_contract_path)
                        echo 'Signed Version not available yet.';
                    else
                        print '<iframe src="data:application/pdf;base64,' . $signed_content . '" height="950px" width="100%"></iframe>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>