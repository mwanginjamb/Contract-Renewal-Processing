<div class="row">
    <div class="col">
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
</div>