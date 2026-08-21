<script>
    $(function () {
        setTimeout(function() {
            $('#updatecheck-loading').addClass('hidden');
            $('#updatecheck-no-updates').removeClass('hidden');
            $('#ipnews-loading').addClass('hidden');
            $('#ipnews-container').removeClass('hidden');
        }, 800);
    });
</script>

<div class="col-xs-12 col-md-8 col-md-offset-2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-refresh fa-margin"></i> <?php echo MIKROTEK_APP_NAME; ?> - Update & Status Center
        </div>
        <div class="panel-body">

            <div class="form-group">
                <label><?php _trans('current_version'); ?></label>
                <input type="text" class="form-control" value="<?php echo MIKROTEK_APP_NAME . ' ' . MIKROTEK_INVOICE_VERSION; ?>" readonly="readonly">
            </div>
            <div id="updatecheck-results">
                <div id="updatecheck-loading" class="btn btn-default btn-sm disabled">
                    <i class="fa fa-circle-o-notch fa-spin"></i> Memeriksa status sistem Mikrotek...
                </div>

                <div id="updatecheck-no-updates" class="alert alert-success hidden" style="margin-bottom: 0;">
                    <i class="fa fa-check-circle fa-margin"></i> <strong>Mikrotek Business Suite v1.2.0 Berjalan dengan Lancar & Aman.</strong>
                    <br><small>Sistem Anda sudah menggunakan versi resmi rilis terbaru Mikrotek dengan penguatan keamanan RBAC dan modul Project aktif.</small>
                </div>
            </div>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-bullhorn fa-margin"></i> Mikrotek Business Suite - Pengumuman & Catatan Rilis
        </div>
        <div class="panel-body">

            <div id="ipnews-results">
                <div id="ipnews-loading" class="btn btn-default btn-sm disabled">
                    <i class="fa fa-circle-o-notch fa-spin"></i> Memuat catatan rilis...
                </div>

                <div id="ipnews-container" class="hidden">
                    <div class="alert alert-info" style="margin-bottom: 10px;">
                        <b>[Rilis v1.2.0] Penguatan Keamanan RBAC & Modul Project</b><br/>
                        Pembaruan v1.2.0 mencakup penguatan otorisasi berbasis Role Access Control (RBAC), proteksi IDOR pada API klien/faktur, penanganan error 403 AJAX, serta modul Project dengan tab transaksi khusus.<br/>
                        <small><b>Tanggal Rilis: 21 Agustus 2026</b></small>
                    </div>
                    <div class="alert alert-success" style="margin-bottom: 0;">
                        <b>[Rilis v1.1.1] Fitur Kuitansi & Tanda Tangan Digital Mikrotek</b><br/>
                        Peningkatan fitur pengelolaan PIC Klien, nomor referensi faktur, dukungan faktur proforma otomatis, dan opsi tanda tangan digital.<br/>
                        <small><b>Tanggal Rilis: 21 Agustus 2026</b></small>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>