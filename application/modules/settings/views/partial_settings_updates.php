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
            <i class="fa fa-info-circle fa-margin"></i> About <?php echo MIKROTEK_APP_NAME; ?>
        </div>
        <div class="panel-body">
            <div class="media">
                <div class="media-left">
                    <img src="<?php echo base_url('assets/core/img/favicon.png'); ?>" alt="Mikrotek Logo" style="width: 64px; height: 64px; margin-right: 15px;">
                </div>
                <div class="media-body">
                    <h4 class="media-heading" style="font-weight: bold; color: #2c3e50;">
                        <?php echo MIKROTEK_APP_NAME; ?> <span class="label label-primary"><?php echo MIKROTEK_INVOICE_VERSION; ?></span>
                    </h4>
                    <p class="text-muted" style="margin-bottom: 15px;">
                        Platform pengelolaan operasional bisnis, sistem invoicing, manajemen multi-perusahaan, kontrol hak akses berbasis peran (RBAC), modul kwitansi terintegrasi Terbilang Bahasa Indonesia, dan kustomisasi PDF profesional.
                    </p>
                </div>
            </div>

            <hr style="margin: 15px 0;">

            <div class="row">
                <div class="col-sm-6">
                    <h5 style="font-weight: bold; color: #34495e;"><i class="fa fa-cubes text-primary"></i> Core Modules & Features:</h5>
                    <ul class="list-unstyled" style="line-height: 1.8;">
                        <li><i class="fa fa-check-square-o text-success"></i> <strong>Role Access Control (RBAC):</strong> Matriks izin modular & Staff role</li>
                        <li><i class="fa fa-check-square-o text-success"></i> <strong>Multi-Company:</strong> Manajemen hirarki master perusahaan</li>
                        <li><i class="fa fa-check-square-o text-success"></i> <strong>Modul Kwitansi:</strong> Terintegrasi Auto-Terbilang Indonesia</li>
                        <li><i class="fa fa-check-square-o text-success"></i> <strong>Proforma Invoicing:</strong> Faktur sementara & konversi 1-klik</li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <h5 style="font-weight: bold; color: #34495e;"><i class="fa fa-sliders text-info"></i> Enhanced Tools & PDF:</h5>
                    <ul class="list-unstyled" style="line-height: 1.8;">
                        <li><i class="fa fa-check-square-o text-success"></i> <strong>Multiple Client PICs:</strong> Pengelolaan kontak PIC per klien</li>
                        <li><i class="fa fa-check-square-o text-success"></i> <strong>Multi-Rekening Bank:</strong> Pilihan bank perusahaan di faktur</li>
                        <li><i class="fa fa-check-square-o text-success"></i> <strong>Project Linkage:</strong> Pengikatan faktur/penawaran ke proyek</li>
                        <li><i class="fa fa-check-square-o text-success"></i> <strong>PDF Kop & Signature:</strong> HTML Kop & Tanda Tangan Digital/Manual</li>
                    </ul>
                </div>
            </div>

            <hr style="margin: 15px 0;">

            <div class="well well-sm" style="margin-bottom: 0; background-color: #f8f9fa;">
                <small class="text-muted">
                    <i class="fa fa-copyright"></i> Customized & Enhanced by <strong>PT Mikrotek Zemiro Indonesia</strong>.<br>
                    <i class="fa fa-heart text-danger"></i> Powered by <strong>InvoicePlane</strong> (Licensed under MIT License). Copyright &copy; InvoicePlane Developers &amp; Contributors.
                </small>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-bullhorn fa-margin"></i> <?php echo MIKROTEK_APP_NAME; ?> - Pengumuman & Catatan Rilis
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